<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LandingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'name',
        'banner_title',
        'banner_subtitle',
        'banner_image',
        'background_color',
        'banner_title_color',
        'banner_subtitle_color',
        'banner_title_size',
        'banner_subtitle_size',
        'plan_title_color',
        'plan_text_color',
        'plan_price_color',
        'plan_title_size',
        'plan_text_size',
        'plan_price_size',
        'plan_title_bg_color',
        'plan_text_bg_color',
        'plan_price_bg_color',
        'section_bg_color',
        'section_text_color',
        'plan_card_border_color',
        'plan_card_border_radius',
        'plan_card_shadow',
        'footer_title_color',
        'footer_text_color',
        'footer_button_text',
        'footer_button_text_color',
        'footer_button_bg_color',
        'footer_button_border_radius',
        'footer_button_url',
        'footer_bg_color',
        'menu_bg_color',
        'menu_text_color',
        'menu_font_size',
        'section_after_plans',
        'menu_items',
        'footer_email',
        'footer_phone',
        'plans',
        'is_published',
    ];

    protected $casts = [
        'menu_items' => 'array',
        'plans' => 'array',
        'is_published' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
