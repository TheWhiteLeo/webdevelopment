<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->blogCategoryRepository->getAllWithPaginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        if (empty($data["slug"])) {
            $data["slug"] = Str::slug($data["title"]);
        }

        $item = BlogCategory::create($data);

        if ($item) {
            return [
                "success" => true,
                "message" => "Успішно створено",
                "data" => $item
            ];
        } else {
            return [
                "success" => false,
                "message" => "Помилка збереження"
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = BlogCategory::find($id);

        if (empty($item)) {
            return [
                "success" => false,
                "message" => "Запис id=[{$id}] не знайдено"
            ];
        }

        return $item;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, string $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return [
                "success" => false,
                "message" => "Запис id=[{$id}] не знайдено"
            ];
        }

        $data = $request->all();
        if (empty($data["slug"])) {
            $data["slug"] = Str::slug($data["title"]);
        }

        $result = $item->update($data);

        if ($result) {
            return [
                "success" => true,
                "message" => "Успішно збережено",
                "data" => $item
            ];
        } else {
            return [
                "success" => false,
                "message" => "Помилка збереження"
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = BlogCategory::find($id);

        if (empty($item)) {
            return [
                "success" => false,
                "message" => "Запис id=[{$id}] не знайдено"
            ];
        }

        $result = $item->delete();

        if ($result) {
            return [
                "success" => true,
                "message" => "Успішно видалено"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Помилка видалення"
            ];
        }
    }
}
