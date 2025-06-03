<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job_title' => $this->job->title ?? null,
            'cover_letter' => $this->cover_letter,
            'cv_url' => $this->cv_path ? asset('storage/' . $this->cv_path) : null,
            'status' =>$this->status,
            'applied_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
