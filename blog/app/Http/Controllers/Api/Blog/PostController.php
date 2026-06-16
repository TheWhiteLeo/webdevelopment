<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Resources\Api\Blog\Admin\PostIndexResource;
use App\Http\Resources\Api\Blog\Admin\PostResource;
use App\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

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

        $paginator = $this->blogPostRepository->getAllWithPaginate($filters);

        return PostIndexResource::collection($paginator);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
