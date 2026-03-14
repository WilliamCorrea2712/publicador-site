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
            $table->string('menu_bg_color')->default('#ffffff');
            $table->string('menu_text_color')->default('#111827');
            $table->string('menu_font_size')->default('1rem');
            $table->text('section_after_plans')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['menu_bg_color', 'menu_text_color', 'menu_font_size', 'section_after_plans']);
        });
    }
};
