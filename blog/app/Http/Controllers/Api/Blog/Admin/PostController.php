<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Resources\Api\Blog\Admin\PostIndexResource;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Http\Resources\Api\Blog\Admin\PostResource;
use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Repositories\BlogPostRepository;


class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository)
    {
        //parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort_by', 'sort_dir', 'per_page']);

        $paginator = $this->blogPostRepository->getAllWithPaginate(filters: $filters);

        return PostIndexResource::collection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostCreateRequest  $request)
    {
        $data = $request->input();

        $item = BlogPost::create($data);

        if ($item) {
            BlogPostAfterCreateJob::dispatch($item);
            return [
                "success" => true,
                "message" => "Пост успішно створено",
                "item" => PostResource::collection([$item])
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
        $post = $this->blogPostRepository->getOne($id);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

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
                "message" => "Пост успішно збережено",
                "item" => PostResource::collection([$item])
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
        $result = BlogPost::destroy($id);

        //$result = BlogPost::find($id)->forceDelete(); //повне видалення з БД

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);
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
