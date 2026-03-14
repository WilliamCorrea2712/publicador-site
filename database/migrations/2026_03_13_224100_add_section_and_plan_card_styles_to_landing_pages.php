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
            $table->string('section_bg_color')->default('#ffffff');
            $table->string('section_text_color')->default('#111827');
            $table->string('plan_card_border_color')->default('#e5e7eb');
            $table->string('plan_card_border_radius')->default('0.5rem');
            $table->string('plan_card_shadow')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['section_bg_color', 'section_text_color', 'plan_card_border_color', 'plan_card_border_radius', 'plan_card_shadow']);
        });
    }
};
