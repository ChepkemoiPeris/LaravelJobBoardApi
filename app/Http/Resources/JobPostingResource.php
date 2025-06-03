<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class JobPostingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->name,
            ],
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary,
            'job_type' => $this->jobType->name,
            'status' => $this->status,
            'deadline' => Carbon::parse($this->deadline)->toDateString(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
