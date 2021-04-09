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

    //visibility
    const VISIBILITY_ABOUT = 5;
    const VISIBILITY_SKILL = 6;
    const VISIBILITY_EDUCATION = 7;
    const VISIBILITY_EXPERIENCE = 8;
    const VISIBILITY_PROJECT = 9;
    const VISIBILITY_SERVICE = 10;
    const VISIBILITY_CONTACT = 11;
    const VISIBILITY_FOOTER = 12;

    const SCRIPT_HEADER = 13;
    const SCRIPT_FOOTER = 14;

    const META_TITLE = 15;
    const META_AUTHOR = 16;
    const META_DESCRIPTION = 17;
    const META_IMAGE = 18;

    const VISIBILITY_CV = 19;
    const VISIBILITY_SKILL_PERCENT = 20;
    

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
