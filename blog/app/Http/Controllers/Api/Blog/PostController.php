<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
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
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort_by', 'sort_dir', 'per_page']);

        return $this->blogPostRepository->getAllWithPaginate($filters);
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
        return $this->blogPostRepository->getOne($id);
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
