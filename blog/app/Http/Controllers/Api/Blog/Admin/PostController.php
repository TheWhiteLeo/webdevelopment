<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Repositories\BlogPostRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->blogPostRepository->getAllWithPaginate();
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
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { //якщо ід не знайдено
            return ["message" => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми

        if (empty($data["slug"])) { //якщо псевдонім порожній
            $data["slug"] = Str::slug($data["title"]); //генеруємо псевдонім
        }
        if (empty($item->published_at) && $data["is_published"]) { //якщо поле published_at порожнє і нам прийшло 1 в ключі is_published, то
            $data["published_at"] = Carbon::now(); //генеруємо поточну дату
        }
        $result = $item->update($data); //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return [
                "success" => true,
                "message" => "Пост успішно збережено",
                "item" => $item
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
