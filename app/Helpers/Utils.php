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
}