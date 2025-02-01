<?php

use App\Enums\Slider\SliderItemMediaType;
use App\Enums\Slider\SliderItemStatus;
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
        Schema::create('slider_items', function (Blueprint $table) {
            $table->id();
            $table->string('media')->nullable();
            $table->boolean('media_type')->default(SliderItemMediaType::PHOTO);
            $table->boolean('is_active')->default(SliderItemStatus::INACTIVE->value);
            $table->foreignId('slider_id')->constrained('sliders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_items');
    }
};
