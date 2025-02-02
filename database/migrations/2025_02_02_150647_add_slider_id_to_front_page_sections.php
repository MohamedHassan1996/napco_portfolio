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
        Schema::table('front_page_sections', function (Blueprint $table) {
            $table->foreignId('slider_id')->nullable()->constrained('sliders')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('front_page_sections', function (Blueprint $table) {
            $table->dropForeign('slider_id');
            $table->dropColumn('slider_id');
        });
    }
};
