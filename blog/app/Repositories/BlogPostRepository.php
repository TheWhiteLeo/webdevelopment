<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogСategoryRepository.
 */
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class; //абстрагування моделі BlogCategory, для легшого створення іншого репозиторія
    }

    /**
     * Отримати список статей
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate(array $filters = [])
    {
        $columns = [
            'blog_posts.id',
            'blog_posts.title',
            'blog_posts.slug',
            'blog_posts.is_published',
            'blog_posts.published_at',
            'blog_posts.user_id',
            'blog_posts.category_id'
        ];

        $query = $this->startConditions()
            ->select($columns)
            ->with([
                'category:id,title',
                'user:id,name',
            ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('blog_posts.id', $search);
                }

                $q->orWhere('blog_posts.title', 'LIKE', "%{$search}%");

                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'author') {
            $query->join('users', 'blog_posts.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortDir);
        } elseif (in_array($sortBy, ['id', 'published_at'])) {
            $query->orderBy("blog_posts.{$sortBy}", $sortDir);
        } else {
            $query->orderBy('blog_posts.id', 'desc');
        }

        $perPage = (int) ($filters['per_page'] ?? 25);

        $allowedPerPage = [10, 20, 25, 50, 100];
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 25;

        return $query->paginate($perPage);
    }

    public function getOne($id)
    {
        return $this->startConditions()
            ->with([
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                },
                // 'category:id,title',
                'user:id,name'
            ])
            ->find($id);
    }
    /**
     *  Отримати модель для редагування в адмінці
     *  @param int $id
     *  @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
