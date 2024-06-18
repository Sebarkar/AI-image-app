<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ModelWithFormResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'form' => $this->form,
            'image' => $this->image,
            'public' => $this->public,
            'title' => $this->title,
            'type' => $this->type,
            'user_fine_tune' => $this->user_fine_tune,
        ];
    }
}
