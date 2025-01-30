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
        Schema::create('slider_item_translations', function (Blueprint $table) {
            $table->id();
            $table->string('media')->nullable();
            $table->string('media_type', 50)->nullable();
            $table->json('content')->nullable();
            $table->unsignedBigInteger('slider_item_id'); // Add event_id column
            $table->string('locale');               // Add locale column for uniqueness constraint
            $table->unique(['slider_item_id', 'locale'], 'slider_item_id_locale_unique');
            $table->foreign('slider_item_id')->references('id')->on('slider_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_item_translations');
    }
};
