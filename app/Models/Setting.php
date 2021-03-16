<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const SITE_NAME = 1;
    const ACCENT_COLOR = 2;
    const NAV_BAR_BACKGROUND = 3;
    const NAV_BAR_COLOR = 4;
    const SIDE_BAR_BACKGROUND = 5;
    const SIDE_BAR_COLOR = 6;
    const SHORT_MENU = 7;
    const LOGO = 8;
    const FAVICON = 9;
    const COVER = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_key',
        'setting_value',
        'default_value'
    ];
}
