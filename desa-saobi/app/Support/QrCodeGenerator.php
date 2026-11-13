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
        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($content);
    }

    /**
     * Hasilkan QR code sebagai data URI SVG agar dapat ditanam langsung pada PDF.
     */
    public static function svgDataUri(string $content, int $size = 180, int $margin = 0): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(self::svg($content, $size, $margin));
    }
}
