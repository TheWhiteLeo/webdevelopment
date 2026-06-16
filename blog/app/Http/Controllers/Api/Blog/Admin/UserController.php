<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Resources\Api\Blog\Admin\UserResource;
use App\Repositories\BlogUserRepository;

class UserController extends BaseController
{
    public function __construct(private BlogUserRepository $blogUserRepository)
    {
        //parent::__construct();
    }

    public function index()
    {
        $users = $this->blogUserRepository->getForComboBox();

        return UserResource::collection($users);
    }
}
