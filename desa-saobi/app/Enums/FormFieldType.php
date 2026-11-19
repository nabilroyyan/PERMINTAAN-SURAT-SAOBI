<?php

namespace App\Enums;

enum FormFieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case DATE = 'date';
    case NUMBER = 'number';
    case SELECT = 'select';
    case RADIO = 'radio';
    case CHECKBOX = 'checkbox';
    case FILE = 'file';

    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'Teks Pendek',
            self::TEXTAREA => 'Teks Panjang',
            self::DATE => 'Tanggal',
            self::NUMBER => 'Angka',
            self::SELECT => 'Pilihan (Dropdown)',
            self::RADIO => 'Pilihan Tunggal (Radio)',
            self::CHECKBOX => 'Pilihan Banyak (Centang)',
            self::FILE => 'Unggah Berkas',
        };
    }

    /**
     * Tipe yang memerlukan daftar opsi pilihan.
     */
    public function hasOptions(): bool
    {
        return in_array($this, [self::SELECT, self::RADIO, self::CHECKBOX], true);
    }

    public static function values(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }
}
