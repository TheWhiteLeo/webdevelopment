<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BlogPost::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (empty($data["slug"]) && !empty($data["title"])) {
            $data["slug"] = Str::slug($data["title"]);
        }

        if (isset($data["content_raw"]) && empty($data["content_html"])) {
            $data["content_html"] = $data["content_raw"];
        }

        if (isset($data["is_published"]) && $data["is_published"] && empty($data["published_at"])) {
            $data["published_at"] = now();
        }

        $item = BlogPost::create($data);

        if ($item) {
            return [
                "success" => true,
                "message" => "Пост успішно створено",
                "id"      => $item->id
            ];
        } else {
            return [
                "success" => false,
                "message" => "Помилка збереження поста"
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = BlogPost::find($id);

        if (empty($item)) {
            return ["message" => "Пост id=[{$id}] не знайдено"];
        }

        return $item;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = BlogPost::find($id);

        if (empty($item)) {
            return ["message" => "Пост id=[{$id}] не знайдено"];
        }

        $data = $request->all();

        if (empty($data["slug"]) && !empty($data["title"])) {
            $data["slug"] = Str::slug($data["title"]);
        }

        if (isset($data["content_raw"]) && empty($data["content_html"])) {
            $data["content_html"] = $data["content_raw"];
        }

        if (isset($data["is_published"]) && $data["is_published"] && empty($data["published_at"])) {
            $data["published_at"] = now();
        }

        $result = $item->update($data);

        if ($result) {
            return [
                "success" => true,
                "message" => "Пост успішно збережено"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Помилка збереження поста"
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = BlogPost::find($id);

        if (empty($item)) {
            return ["message" => "Пост id=[{$id}] не знайдено"];
        }

        $result = $item->delete();

        if ($result) {
            return [
                "success" => true,
                "message" => "Пост успішно видалено"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Помилка видалення поста"
            ];
        }
    }
}
