<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Resources\Api\Blog\Admin\CategoryResource;
use App\Models\BlogCategory;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['nopaginate']);
        if ($filters['nopaginate']) {
            $categories = $this->blogCategoryRepository->getForComboBox();

            return CategoryResource::collection($categories);
        } else {
            $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

            return  CategoryResource::collection($paginator);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        $item = BlogCategory::create($data);

        if ($item) {
            return [
                "success" => true,
                "message" => "Успішно створено",
                "data" => CategoryResource::collection([$item])
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
        $category = $this->blogCategoryRepository->getOne($id);

        return CategoryResource::collection([$category]);
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

        $result = $item->update($data);

        if ($result) {
            return [
                "success" => true,
                "message" => "Успішно збережено",
                "data" => CategoryResource::collection([$item])
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
        $result = BlogPost::destroy($id);

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
