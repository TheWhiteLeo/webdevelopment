<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;

class CategoryResource extends CategoryIndexResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData = parent::toArray($request);

        $detailed_data =[
            'parent_id' => $this->parent_id,
        ];

        return array_merge($baseData, $detailed_data);
    }
}
