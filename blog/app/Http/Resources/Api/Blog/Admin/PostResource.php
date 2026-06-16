<?php

namespace App\Http\Resources\Api\Blog\Admin;

class PostResource extends PostIndexResource
{
    public function toArray($request)
    {
        $baseData = parent::toArray($request);

        $detailedData = [
            'category_id'  => $this->category_id,
            'author_id'    => $this->user_id,
            'content_raw'  => $this->content_raw,
            'content_html' => $this->content_html,
        ];

        return array_merge($baseData, $detailedData);
    }
}
