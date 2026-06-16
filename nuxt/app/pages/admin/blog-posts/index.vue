<template>
  <div class="max-w-7xl mx-auto py-8 space-y-6">
    <SharedAppHeader />
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold">Список постів</h1>
      <UButton
        label="Створити пост"
        icon="i-lucide-plus"
        color="primary"
        @click="navigateTo('/admin/blog-posts/create')"
      />
    </div>

    <SharedDataTable
      v-model:search="globalFilter"
      v-model:page="currentPage"
      v-model:items-per-page="selectedPerPage"
      v-model:sorting="sorting"
      :data="currentPagePosts"
      :columns="columns"
      :total="total"
      :pending="pending"
      search-placeholder="Пошук по ID, Автору, Заголовку..."
      @row-select="(row) => navigateTo(`/admin/blog-posts/${row.id}`)"
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
    </SharedDataTable>
  </div>
</template>

<script setup lang="ts">
import type { DropdownMenuItem } from '@nuxt/ui'
import type { PostIndex } from '~/types/PostIndex'

const {
  currentPage,
  globalFilter,
  selectedPerPage,
  sorting,
  pending,
  currentPagePosts,
  total,
  deletePost
} = useBlogPosts(true)

// Та ж сама зручна функція
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

const columns = [
  { accessorKey: 'id', header: renderSortableHeader('#', 'id') },
  { accessorKey: 'author_name', header: renderSortableHeader('Автор', 'author_name') },
  { accessorKey: 'category_title', header: renderSortableHeader('Категорія', 'category_title') },
  { accessorKey: 'title', header: renderSortableHeader('Заголовок', 'title') },
  { accessorKey: 'published_at', header: renderSortableHeader('Дата публікації', 'published_at') },
  { id: 'actions', header: '' }
]

const handleDeletePost = async (id: string | number, title: string) => {
  if (confirm(`Ви впевнені, що хочете видалити пост "${title}"?`)) {
    await deletePost(id)
  }
}

function getDropdownActions(post: PostIndex): DropdownMenuItem[][] {
  return [
    [
      { label: 'Редагувати', icon: 'i-lucide-edit', onSelect: () => navigateTo({ path: `/admin/blog-posts/${post.id}`, query: { edit: 'true' } }) }
    ],
    [
      { label: 'Видалити', icon: 'i-lucide-trash', color: 'error', onSelect: () => handleDeletePost(post.id, post.title) }
    ]
  ]
}
</script>
