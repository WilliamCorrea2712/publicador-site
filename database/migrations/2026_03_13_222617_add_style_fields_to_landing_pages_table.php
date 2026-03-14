<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('banner_title_color')->default('#ffffff');
            $table->string('banner_subtitle_color')->default('#f3f4f6');
            $table->string('banner_title_size')->default('2.25rem');
            $table->string('plan_title_color')->default('#111827');
            $table->string('plan_text_color')->default('#374151');
            $table->string('plan_price_color')->default('#000000');
            $table->string('plan_title_size')->default('1rem');
            $table->string('plan_text_size')->default('0.9rem');
            $table->string('plan_price_size')->default('1rem');
            $table->string('footer_bg_color')->default('#111827');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn([ 
                'banner_title_color',
                'banner_subtitle_color',
                'banner_title_size',
                'plan_title_color',
                'plan_text_color',
                'plan_price_color',
                'plan_title_size',
                'plan_text_size',
                'plan_price_size',
                'footer_bg_color',
            ]);
        });
    }
};
