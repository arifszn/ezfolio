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
