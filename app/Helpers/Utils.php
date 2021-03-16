<?php

class Utils {
    public static function getFavicon()
    {
        try {
            return asset('assets/common/img/favicon/default_favicon.png');
        } catch (\Throwable $th) {
            return asset('assets/common/img/favicon/default_favicon.png');
        }
    }
    
    /**
     * Return rgb value of a hex color value
     * 
     * @param string $hex 
     * @return string //r, g, b
     */
    public static function getRgbValue(string $hex)
    {
        $split = str_split(str_replace("#", "", $hex), 2);
        $r = hexdec($split[0]);
        $g = hexdec($split[1]);
        $b = hexdec($split[2]);
        $accentColorRGB = $r . ", " . $g . ", " . $b; //e.g. 255, 255, 255
        
        return $accentColorRGB;
    }
}