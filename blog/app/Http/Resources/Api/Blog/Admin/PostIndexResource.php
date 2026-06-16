<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PostIndexResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'slug'            => $this->slug,

            'is_published'    => (bool) $this->is_published,
            'published_at'    => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,

            'category_title'  => $this->category?->title,
            'author_name'     => $this->user?->name,
        ];
    }
}
