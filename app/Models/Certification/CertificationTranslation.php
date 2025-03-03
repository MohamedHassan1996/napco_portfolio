<?php

namespace App\Models\Certification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CertificationTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description'];

    public $timestamps = false;

    protected $casts = [
        'description' => 'array',
    ];


}
