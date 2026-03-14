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
            $table->string('plan_title_bg_color')->default('#1d4ed8');
            $table->string('plan_text_bg_color')->default('#f3f4f6');
            $table->string('plan_price_bg_color')->default('#10b981');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['plan_title_bg_color', 'plan_text_bg_color', 'plan_price_bg_color']);
        });
    }
};
