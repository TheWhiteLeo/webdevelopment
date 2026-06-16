<template>
  <div class="max-w-7xl mx-auto py-8 space-y-6">
    <PostsAppHeader />
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold">Список категорій</h1>
      <UButton
        label="Додати категорію"
        icon="i-lucide-plus"
        color="primary"
        @click="navigateTo('/admin/categories/create')"
      />
    </div>

    <div class="flex items-center justify-between gap-4 px-4 py-3.5 border-b border-accented">
      <UInput
        v-model="globalFilter"
        icon="i-heroicons-magnifying-glass"
        placeholder="Пошук категорій..."
        class="w-64"
      />
    </div>

    <UTable
      :columns="columns"
      :data="categories"
      :loading="pending"
    >
      <template #actions-cell="{ row }">
        <UDropdownMenu :items="getDropdownActions(row.original)">
          <UButton
            icon="i-lucide-ellipsis-vertical"
            color="neutral"
            variant="ghost"
            aria-label="Дії"
          />
        </UDropdownMenu>
      </template>
    </UTable>

    <div class="flex justify-end pt-4 mt-4 border-t border-gray-100">
      <UPagination
        v-model:page="currentPage"
        :items-per-page="selectedPerPage"
        :total="total"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'
import type { Category } from '~/types/Category'

const {
  currentPage,
  globalFilter,
  selectedPerPage,
  pending,
  categories,
  total,
  deleteCategory
} = useBlogCategories()

const columns = [
  { accessorKey: 'id', header: '#' },
  { accessorKey: 'title', header: 'Назва категорії' },
  { accessorKey: 'parent_category', header: 'Батьківська категорія' },
  { id: 'actions', header: '' }
]

const handleDelete = async (category: Category) => {
  if (confirm(`Ви впевнені, що хочете видалити категорію "${category.title}"?`)) {
    await deleteCategory(category.id)
  }
}

function getDropdownActions(category: Category): DropdownMenuItem[][] {
  return [
    [
      {
        label: 'Перегляд',
        icon: 'i-lucide-eye',
        onSelect: () => navigateTo(`/admin/categories/${category.id}`)
      },
      {
        label: 'Редагувати',
        icon: 'i-lucide-edit',
        onSelect: () => navigateTo({ path: `/admin/categories/${category.id}`, query: { edit: 'true' } })
      }
    ],
    [
      {
        label: 'Видалити',
        icon: 'i-lucide-trash',
        color: 'error',
        onSelect: () => handleDelete(category)
      }
    ]
  ]
}
</script>
