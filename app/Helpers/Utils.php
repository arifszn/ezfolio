<?php

use App\Models\Setting;

class Utils {
    public static function isShortMenu()
    {
        try {
            $settingService = resolve(SettingContract::class);
            $response = $settingService->getSettingByKey(Setting::SHORT_MENU, ['setting_value']);

            if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                if ($response['payload']->setting_value === true || $response['payload']->setting_value === 'true' || $response['payload']->setting_value === 1 || $response['payload']->setting_value === '1') {
                    return true;
                };
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getFavicon()
    {
        try {
            $settingService = resolve(SettingContract::class);
            $response = $settingService->getSettingByKey(Setting::FAVICON, ['setting_value']);

            if ($response['status'] === Constants::STATUS_CODE_SUCCESS) {
                return asset($response['payload']->setting_value);
            } else {
                return asset('assets/common/img/favicon/default_favicon.png');
            }
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