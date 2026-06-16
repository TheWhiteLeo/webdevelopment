<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Database\Eloquent\Collection;

class BlogUserRepository extends CoreRepository
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
     * Отримати список користувачів для випадаючого списку (без пагінації)
     * * @return Collection
     */
    public function getForComboBox(): Collection
    {
        // Беремо лише необхідні колонки для оптимізації
        $columns = ['id', 'name', 'profile_photo_path'];

        return $this
            ->getBaseQuery($columns)
            ->get();
    }
}
