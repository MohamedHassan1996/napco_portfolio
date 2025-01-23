<?php

namespace App\Models\CompanyTeam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanyTeam extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'job_title', 'social_links', 'image', 'is_active'];

    protected $casts = [
        'social_links' => 'array',
    ];



}
