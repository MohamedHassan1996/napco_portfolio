<?php

namespace App\Models\Certification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Certification extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $translatedAttributes = ['title', 'description'];
    protected $fillable = ['is_published', 'image'];


}
