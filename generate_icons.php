<?php
// Generates the web logo, PWA icons and favicon from the Kemenag logo.
// Usage: php generate_icons.php [path/to/source-logo.png]
$source = $argv[1] ?? __DIR__ . '/public/images/logo-kemenag.png';
$src = imagecreatefrompng($source);
if (! $src) {
    exit("Cannot read logo: {$source}\n");
}
$srcW = imagesx($src);
$srcH = imagesy($src);

function transparentCanvas(int $w, int $h)
{
    $im = imagecreatetruecolor($w, $h);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    imagefill($im, 0, 0, imagecolorallocatealpha($im, 0, 0, 0, 127));

    return $im;
}

// Logo used across the site
$logoW = 400;
$logoH = (int) round($srcH * $logoW / $srcW);
$logo = transparentCanvas($logoW, $logoH);
imagecopyresampled($logo, $src, 0, 0, 0, 0, $logoW, $logoH, $srcW, $srcH);
@mkdir(__DIR__ . '/public/images', 0755, true);
imagepng($logo, __DIR__ . '/public/images/logo-kemenag.png');

// PWA icons: logo centered on a white square
foreach ([192, 512] as $size) {
    $im = imagecreatetruecolor($size, $size);
    imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));
    $w = (int) round($size * 0.8);
    $h = (int) round($srcH * $w / $srcW);
    imagecopyresampled($im, $src, (int) (($size - $w) / 2), (int) (($size - $h) / 2), 0, 0, $w, $h, $srcW, $srcH);
    imagepng($im, __DIR__ . '/public/icon-' . $size . 'x' . $size . '.png');
}

// Favicon: 48x48 PNG wrapped in an ICO container
$size = 48;
$im = transparentCanvas($size, $size);
$h = (int) round($srcH * $size / $srcW);
imagecopyresampled($im, $src, 0, (int) (($size - $h) / 2), 0, 0, $size, $h, $srcW, $srcH);
ob_start();
imagepng($im);
$png = ob_get_clean();
$ico = pack('vvv', 0, 1, 1) . pack('CCCCvvVV', $size, $size, 0, 0, 1, 32, strlen($png), 22) . $png;
file_put_contents(__DIR__ . '/public/favicon.ico', $ico);

echo "Icons generated.\n";
