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
     * Базовий приватний метод для формування запиту
     * * @param array $columns
     * @param array $with Зв'язки для підвантаження (Eager Loading)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBaseQuery(array $columns = ['*'], array $with = [])
    {
        return $this->startConditions()
            ->select($columns)
            ->with($with);
    }

    /**
     * Отримати список категорій для випадаючого списку (без пагінації)
     * * @return Collection
     */
    public function getForComboBox(): Collection
    {
        // Беремо лише необхідні колонки для оптимізації
        $columns = ['id', 'title', 'slug', 'parent_id'];

        return $this
            ->getBaseQuery($columns, ['parentCategory:id,title'])
            ->get();
    }

    /**
     * Отримати категорії для таблиці з пагінацією
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(?int $perPage = null): LengthAwarePaginator
    {
        $columns = ['id', 'title', 'slug', 'parent_id'];

        return $this
            ->getBaseQuery($columns, ['parentCategory:id,title'])
            ->paginate($perPage);
    }

    /**
     * Отримати модель для редагування
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
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
}
