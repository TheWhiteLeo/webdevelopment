<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Отримати список категорій з фільтрацією, сортуванням та підтримкою nopaginate
     *
     * @param array $filters
     * @return LengthAwarePaginator|Collection
     */
    public function getAllWithPaginate(array $filters = [])
    {
        $columns = [
            'blog_categories.id',
            'blog_categories.title',
            'blog_categories.slug',
            'blog_categories.parent_id'
        ];

        $query = $this->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title']);

        // 1. Пошук (Search)
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                // Пошук за ID
                if (is_numeric($search)) {
                    $q->where('blog_categories.id', $search);
                }

                // Пошук за назвою категорії
                $q->orWhere('blog_categories.title', 'LIKE', "%{$search}%");

                // Пошук за назвою батьківської категорії
                $q->orWhereHas('parentCategory', function ($parentQuery) use ($search) {
                    $parentQuery->where('title', 'LIKE', "%{$search}%");
                });
            });
        }

        // 2. Сортування (Sorting)
        $sortBy = $filters['sort_by'] ?? 'id';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'parent_category') {
            // Використовуємо leftJoin, оскільки parent_id може бути null
            $query->leftJoin('blog_categories as parents', 'blog_categories.parent_id', '=', 'parents.id')
                ->orderBy('parents.title', $sortDir);
        } elseif (in_array($sortBy, ['id', 'title', 'slug'])) {
            $query->orderBy("blog_categories.{$sortBy}", $sortDir);
        } else {
            $query->orderBy('blog_categories.id', 'desc');
        }

        // 3. Перевірка на nopaginate
        if (!empty($filters['nopaginate']) && $filters['nopaginate'] == true) {
            return $query->get();
        }

        // 4. Пагінація
        $perPage = (int) ($filters['per_page'] ?? 25);
        $allowedPerPage = [5, 10, 20, 25, 50, 100];
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 25;

        return $query->paginate($perPage);
    }

    /**
     * Отримати список категорій для випадаючого списку
     * (Можна залишити для зворотної сумісності або видалити,
     * якщо всюди використовуватиметься getAllWithPaginate(['nopaginate' => true]))
     *
     * @return Collection
     */
    public function getForComboBox(): Collection
    {
        $columns = ['id', 'title', 'slug', 'parent_id'];

        return $this->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title'])
            ->get();
    }

    /**
     * Отримати модель для редагування
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати одну категорію (з підвантаженням зв'язків для консистентності з постами)
     */
    public function getOne($id)
    {
        return $this->startConditions()
            ->with(['parentCategory:id,title'])
            ->find($id);
    }
}
