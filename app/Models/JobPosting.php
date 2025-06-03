<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    /** @use HasFactory<\Database\Factories\JobPostingFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'min_salary',
        'max_salary',
        'job_type_id',
        'status',
        'deadline',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }
}
