<template>
  <div class="max-w-7xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold">Список постів</h1>
    </div>

    <SharedDataTable
      v-model:search="globalFilter"
      v-model:page="currentPage"
      v-model:items-per-page="selectedPerPage"
      v-model:sort="sorting"
      :data="currentPagePosts"
      :columns="columns"
      :posts="currentPagePosts"
      :total="total"
      :pending="pending"
      search-placeholder="Пошук по ID, Автору, Заголовку..."
      @row-select="(row) => navigateTo(`/admin/blog-posts/${row.id}`)"
    />
  </div>
</template>

<script setup lang="ts">
const {
  currentPage,
  globalFilter,
  selectedPerPage,
  sorting,
  pending,
  currentPagePosts,
  total
} = useBlogPosts()

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
]

</script>
