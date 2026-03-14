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
            $table->string('footer_title_color')->default('#ffffff');
            $table->string('footer_text_color')->default('#d1d5db');
            $table->string('footer_button_text')->default('Fale com a gente');
            $table->string('footer_button_text_color')->default('#ffffff');
            $table->string('footer_button_bg_color')->default('#3b82f6');
            $table->string('footer_button_border_radius')->default('0.35rem');
            $table->string('footer_button_url')->default('#contato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn([
                'footer_title_color',
                'footer_text_color',
                'footer_button_text',
                'footer_button_text_color',
                'footer_button_bg_color',
                'footer_button_border_radius',
                'footer_button_url',
            ]);
        });
    }
};
