<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Job;
class JobType extends Model
{
    /** @use HasFactory<\Database\Factories\JobTypeFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = []; 

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
