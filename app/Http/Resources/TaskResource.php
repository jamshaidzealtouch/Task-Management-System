<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'created_by' => $this->created_by,
            'assignee_id' => $this->assignee_id,
            'assignment_status' => $this->assignment_status,

            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'status' => $this->status,

            'project' => $this->project,
            'creator' => $this->creator,
            'assignee' => $this->assignee,
        ];
    }
}
