<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioConfig extends Model
{
    use HasFactory;

    const TEMPLATE = 1;
    const ACCENT_COLOR = 2;
    const GOOGLE_ANALYTICS_ID = 3;
    const MAINTENANCE_MODE = 4;

    //menu
    const MENU_ABOUT = 5;
    const MENU_SKILL = 6;
    const MENU_EDUCATION = 7;
    const MENU_EXPERIENCE = 8;
    const MENU_PROJECT = 9;
    const MENU_SERVICE = 10;
    const MENU_CONTACT = 11;
    const MENU_FOOTER = 12;

    const SCRIPT_HEADER = 13;
    const SCRIPT_FOOTER = 14;

    const META_TITLE = 15;
    const META_AUTHOR = 16;
    const META_DESCRIPTION = 17;
    const META_IMAGE = 18;
    

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
