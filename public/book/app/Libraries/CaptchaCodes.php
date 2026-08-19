<?php

namespace App\Libraries;

class CaptchaCodes
{
    protected $code;

    public function __construct()
    {
        // Constructor can be used for initialization if needed
    }

    /**
     * Generate CAPTCHA image
     */
    public function phpcaptcha(string $textColor, string $backgroundColor, int $imgWidth, int $imgHeight, int $noiceLines = 0, int $noiceDots = 0, string $noiceColor = '#3fbbac')
    {
        $text = $this->random(); 
        $this->code = $text; // Store the CAPTCHA code for validation
      
        $font = FCPATH . 'webroot/fonts/monofont.ttf'; // Path to a TTF font file
        if (!file_exists($font)) {
            throw new \RuntimeException('Font file not found: ' . $font);
        }

        $textColorRGB = $this->hexToRGB($textColor);
        $fontSize = $imgHeight * 0.75;

        $im = imagecreatetruecolor($imgWidth, $imgHeight);
        $textColorAllocated = imagecolorallocate($im, $textColorRGB['r'], $textColorRGB['g'], $textColorRGB['b']);

        $backgroundColorRGB = $this->hexToRGB($backgroundColor);
        $backgroundColorAllocated = imagecolorallocate($im, $backgroundColorRGB['r'], $backgroundColorRGB['g'], $backgroundColorRGB['b']);

        // Generate noise lines if required
        if ($noiceLines > 0) {
            $noiceColorRGB = $this->hexToRGB($noiceColor);
            $noiceColorAllocated = imagecolorallocate($im, $noiceColorRGB['r'], $noiceColorRGB['g'], $noiceColorRGB['b']);
            for ($i = 0; $i < $noiceLines; $i++) {
                imageline($im, mt_rand(0, $imgWidth), mt_rand(0, $imgHeight), mt_rand(0, $imgWidth), mt_rand(0, $imgHeight), $noiceColorAllocated);
            }
        }

        // Generate noise dots if required
        if ($noiceDots > 0) {
            for ($i = 0; $i < $noiceDots; $i++) {
                imagefilledellipse($im, mt_rand(0, $imgWidth), mt_rand(0, $imgHeight), 3, 3, $textColorAllocated);
            }
        }

        imagefill($im, 0, 0, $backgroundColorAllocated);
        [$x, $y] = $this->ImageTTFCenter($im, $text, $font, $fontSize);
        imagettftext($im, $fontSize, 0, $x, $y, $textColorAllocated, $font, $text);
        session()->set('captcha_code', $text); 
        // Output the image
        header('Content-Type: image/jpeg');
        imagejpeg($im, null, 90);
        imagedestroy($im);

     /*    session(['captcha_code' => $text]);
        session()->forget($text); */
 
        
        

    }

    /**
     * Generate a random string for CAPTCHA
     */
    protected function random(int $characters = 6, string $letters = '23456789bcdfghjkmnpqrstvwxyz'): string
    {
        return substr(str_shuffle(str_repeat($letters, ceil($characters / strlen($letters)))), 0, $characters);
    }

    /**
     * Convert hex value to RGB array
     */
    protected function hexToRGB(string $colour): array
    {
        if ($colour[0] === '#') {
            $colour = substr($colour, 1);
        }

        if (strlen($colour) === 6) {
            [$r, $g, $b] = [$colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]];
        } elseif (strlen($colour) === 3) {
            [$r, $g, $b] = [$colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]];
        } else {
            throw new \InvalidArgumentException('Invalid hex color format');
        }

        return [
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b),
        ];
    }

    /**
     * Calculate the center position for CAPTCHA text
     */
    protected function ImageTTFCenter($image, string $text, string $font, float $size, float $angle = 0): array
    {
        $xi = imagesx($image);
        $yi = imagesy($image);

        $box = imagettfbbox($size, $angle, $font, $text);
        $xr = abs(max($box[2], $box[4]));
        $yr = abs(max($box[5], $box[7]));
        $x = intval(($xi - $xr) / 2);
        $y = intval(($yi + $yr) / 2);
        return [$x, $y];
    }

    /**
     * Validate CAPTCHA input
     */
    public function validate($input) 
    { 
        return ($input === $this->code) ? 'yes' : 'no';
    }
}