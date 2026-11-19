<?php

namespace App\Support;

use App\Enums\FormFieldType;
use Illuminate\Support\Str;

class FormFieldSchema
{
    /**
     * Pilihan sumber nilai otomatis dari data penduduk.
     */
    public const DEFAULT_SOURCES = [
        'nama' => 'Nama Lengkap',
        'nik' => 'NIK',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'jenis_kelamin' => 'Jenis Kelamin',
        'agama' => 'Agama',
        'pekerjaan' => 'Pekerjaan',
        'status_perkawinan' => 'Status Perkawinan',
        'dusun' => 'Dusun',
        'rt' => 'RT',
        'rw' => 'RW',
        'alamat' => 'Alamat',
        'telepon' => 'Telepon',
    ];

    /**
     * Rapikan definisi field isian yang dikirim dari editor jenis surat.
     */
    public static function normalize(array $raw): array
    {
        $reserved = SuratTemplateRenderer::formFieldReservedKeys();
        $result = [];
        $seen = [];

        foreach ($raw as $item) {
            if (!is_array($item)) {
                continue;
            }

            $label = trim((string) ($item['label'] ?? ''));
            $kode = trim((string) ($item['kode'] ?? ''));

            if ($label === '' && $kode === '') {
                continue;
            }

            $kode = Str::slug($kode !== '' ? $kode : $label, '_');
            if ($kode === '') {
                continue;
            }

            $kode = self::uniqueKey($kode, $reserved, $seen);
            $seen[] = $kode;

            $tipe = (string) ($item['tipe'] ?? FormFieldType::TEXT->value);
            if (!in_array($tipe, FormFieldType::values(), true)) {
                $tipe = FormFieldType::TEXT->value;
            }

            $options = [];
            if (in_array($tipe, [FormFieldType::SELECT->value, FormFieldType::RADIO->value, FormFieldType::CHECKBOX->value], true)) {
                $rawOptions = $item['options'] ?? [];
                if (is_string($rawOptions)) {
                    $rawOptions = preg_split('/\r\n|\r|\n/', $rawOptions);
                }
                foreach ((array) $rawOptions as $option) {
                    $option = trim((string) $option);
                    if ($option !== '') {
                        $options[] = $option;
                    }
                }
            }

            $result[] = [
                'kode' => $kode,
                'label' => $label !== '' ? $label : Str::headline($kode),
                'tipe' => $tipe,
                'wajib' => (bool) ($item['wajib'] ?? false),
                'placeholder' => trim((string) ($item['placeholder'] ?? '')) ?: null,
                'help' => trim((string) ($item['help'] ?? '')) ?: null,
                'default_from' => isset(self::DEFAULT_SOURCES[$item['default_from'] ?? '']) ? $item['default_from'] : null,
                'options' => $options,
            ];
        }

        return $result;
    }

    /**
     * Susun aturan validasi Laravel untuk seluruh field dinamis.
     *
     * @return array{0: array, 1: array} [rules, attributes]
     */
    public static function validationRules(array $fields): array
    {
        $rules = [];
        $attributes = [];

        foreach ($fields as $field) {
            $kode = $field['kode'];
            $tipe = $field['tipe'] ?? FormFieldType::TEXT->value;
            $wajib = (bool) ($field['wajib'] ?? false);

            $rule = [$wajib ? 'required' : 'nullable'];

            switch ($tipe) {
                case FormFieldType::DATE->value:
                    $rule[] = 'date';
                    break;
                case FormFieldType::NUMBER->value:
                    $rule[] = 'numeric';
                    break;
                case FormFieldType::FILE->value:
                    $rule[] = 'file';
                    $rule[] = 'mimes:jpg,jpeg,png,pdf';
                    $rule[] = 'max:2048';
                    break;
                case FormFieldType::SELECT->value:
                case FormFieldType::RADIO->value:
                    $rule[] = 'string';
                    if (!empty($field['options'])) {
                        $rule[] = 'in:' . implode(',', $field['options']);
                    }
                    break;
                case FormFieldType::CHECKBOX->value:
                    $rule[] = 'array';
                    if (!empty($field['options'])) {
                        $rules["tambahan.{$kode}.*"] = ['string', 'in:' . implode(',', $field['options'])];
                    }
                    break;
                default:
                    $rule[] = 'string';
                    $rule[] = 'max:2000';
            }

            $rules["tambahan.{$kode}"] = $rule;
            $attributes["tambahan.{$kode}"] = $field['label'] ?? $kode;
        }

        return [$rules, $attributes];
    }

    private static function uniqueKey(string $base, array $reserved, array $seen): string
    {
        $kode = $base;
        $i = 1;

        while (in_array($kode, $reserved, true) || in_array($kode, $seen, true)) {
            $i++;
            $kode = $base . '_' . $i;
        }

        return $kode;
    }
}
