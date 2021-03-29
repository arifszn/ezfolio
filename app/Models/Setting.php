<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    const SITE_NAME = 1;
    const ACCENT_COLOR = 2;
    const SHORT_MENU = 3;
    const LOGO = 4;
    const FAVICON = 5;
    const COVER = 6;
    const MENU_LAYOUT = 7;

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
