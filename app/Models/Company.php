<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job;
class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'industry',
        'address',
        'website',
    ];

    // relationship to users (company users)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // relationship to job postings
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
