<template>
  <div class="max-w-7xl mx-auto py-8 space-y-6">
    <SharedAppHeader />
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold">Список категорій</h1>
      <UButton
        label="Додати категорію"
        icon="i-lucide-plus"
        color="primary"
        @click="navigateTo('/admin/categories/create')"
      />
    </div>

    <SharedDataTable
      v-model:search="globalFilter"
      v-model:page="currentPage"
      v-model:items-per-page="selectedPerPage"
      v-model:sorting="sorting"
      :data="categories"
      :columns="columns"
      :total="total"
      :pending="pending"
      search-placeholder="Пошук категорій..."
      @row-select="(row) => navigateTo(`/admin/categories/${row.id}`)"
    >
      <!-- ^^^ Ми додали подію @row-select, щоб рядок був клікабельним ^^^ -->

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
    </SharedDataTable>
  </div>
</template>

<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'
import type { Category } from '~/types/Category'
import {UButton} from "#components";

const {
  currentPage,
  globalFilter,
  selectedPerPage,
  sorting,
  pending,
  categories,
  total,
  deleteCategory
} = useBlogCategories()

// Функція-генератор для сортованих заголовків
const renderSortableHeader = (label: string, columnId: string) => {
  // МАГІЯ ТУТ: повертаємо функцію, яка буде рендеритись динамічно!
  return () => {
    // Тепер це значення читається НАНОВО при кожному кліку та зміні стану
    const currentSort = sorting.value[0]
    const isCurrentColumn = currentSort?.id === columnId

    let icon = 'i-lucide-arrow-up-down'
    if (isCurrentColumn) {
      icon = currentSort.desc
        ? 'i-lucide-arrow-down-wide-narrow'
        : 'i-lucide-arrow-up-narrow-wide'
    }

    const UButton = resolveComponent('UButton')

    return h(UButton, {
      color: 'neutral',
      variant: 'ghost',
      label: label,
      icon: icon,
      class: '-mx-2.5',
      onClick: () => {
        if (isCurrentColumn) {
          // Завдяки тому, що функція викликається постійно, currentSort.desc завжди актуальний
          sorting.value = [{ id: columnId, desc: !currentSort.desc }]
        } else {
          sorting.value = [{ id: columnId, desc: false }]
        }
      }
    })
  }
}

// Використовуємо наш генератор
const columns = [
  { accessorKey: 'id', header: renderSortableHeader('#', 'id') },
  { accessorKey: 'title', header: renderSortableHeader('Назва категорії', 'title') },
  { accessorKey: 'parent_category', header: renderSortableHeader('Батьківська категорія', 'parent_category') },
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
      { label: 'Перегляд', icon: 'i-lucide-eye', onSelect: () => navigateTo(`/admin/categories/${category.id}`) },
      { label: 'Редагувати', icon: 'i-lucide-edit', onSelect: () => navigateTo({ path: `/admin/categories/${category.id}`, query: { edit: 'true' } }) }
    ],
    [
      { label: 'Видалити', icon: 'i-lucide-trash', color: 'error', onSelect: () => handleDelete(category) }
    ]
  ]
}
</script>
