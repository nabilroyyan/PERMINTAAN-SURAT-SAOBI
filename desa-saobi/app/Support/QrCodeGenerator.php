<?php

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeGenerator
{
    /**
     * Hasilkan QR code sebagai string SVG.
     */
    public static function svg(string $content, int $size = 180, int $margin = 0): string
    {
        if (!class_exists(SvgImageBackEnd::class)) {
            throw new \RuntimeException(
                'Library QR (bacon/bacon-qr-code) belum terpasang. Jalankan: composer install'
            );
        }

        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($content);
    }

    /**
     * Hasilkan QR code sebagai data URI SVG agar dapat ditanam langsung pada PDF.
     * Mengembalikan null bila QR tidak dapat dibuat, supaya surat/pratinjau tetap tampil.
     */
    public static function svgDataUri(string $content, int $size = 180, int $margin = 0): ?string
    {
        try {
            return 'data:image/svg+xml;base64,' . base64_encode(self::svg($content, $size, $margin));
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }
}
