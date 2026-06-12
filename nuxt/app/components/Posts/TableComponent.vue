<template>
  <div class="w-full space-y-4 pb-4">
    <div class="flex items-center justify-between gap-4 px-4 py-3.5 border-b border-accented">
      <UInput
        v-model="globalFilter"
        class="max-w-sm w-64"
        placeholder="Пошук по ID, Автору, Заголовку..."
        icon="i-heroicons-magnifying-glass-20-solid"
      />

      <UDropdownMenu
        :items="perPageItems"
        :content="{ align: 'end' }"
      >
        <UButton
          :label="`Показувати: ${selectedPerPage}`"
          color="neutral"
          variant="outline"
          trailing-icon="i-lucide-chevron-down"
          aria-label="Вибір кількості записів на сторінку"
        />
      </UDropdownMenu>
    </div>

    <UTable
      v-model:sorting="sorting"
      :data="currentPagePosts"
      :columns="columns"
      :loading="pending"
      class="flex-1"
    />

    <div class="flex justify-end pt-4 px-4 border-t border-default">
      <UPagination
        v-model:page="currentPage"
        :items-per-page="perPage"
        :total="total"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { h, resolveComponent, computed, ref, watch } from 'vue'
import { refDebounced } from '@vueuse/core'
import type { TableColumn } from '@nuxt/ui'

import type { Post } from '~/types/Post'
import type { LaravelPaginatedResponse } from '~/types/LaravelPaginatedResponse'

const UButton = resolveComponent('UButton')

const currentPage = ref(1)

const globalFilter = ref('')
const debouncedSearch = refDebounced(globalFilter, 500)

const selectedPerPage = ref(25)

const sorting = ref([{ id: 'id', desc: true }])


const apiQuery = computed(() => {
  const sortCol = sorting.value[0]

  return {
    page: currentPage.value,
    per_page: selectedPerPage.value,
    search: debouncedSearch.value,
    sort_by: sortCol?.id || 'id',
    sort_dir: sortCol?.desc ? 'desc' : 'asc'
  }
})


const { data: response, pending } = await useLaravelFetch<LaravelPaginatedResponse>('/blog/posts', {
  query: apiQuery
})

const currentPagePosts = computed<Post[]>(() => response.value?.data || [])
const total = computed<number>(() => response.value?.total || 0)
const perPage = computed<number>(() => response.value?.per_page || 25)


const perPageOptions = [10, 20, 25, 50, 100]

const perPageItems = computed(() => {
  return perPageOptions.map(value => ({
    label: `${value} записів`,
    type: 'checkbox' as const,
    checked: selectedPerPage.value === value,
    onUpdateChecked(checked: boolean) {
      if (checked) {
        selectedPerPage.value = value
      }
    }
  }))
})


const renderSortableHeader = (label: string, column: any) => {
  const isSorted = column.getIsSorted()

  return h(UButton, {
    color: 'neutral',
    variant: 'ghost',
    label: label,

    icon: isSorted
      ? isSorted === 'asc'
        ? 'i-lucide-arrow-up-narrow-wide'
        : 'i-lucide-arrow-down-wide-narrow'
      : 'i-lucide-arrow-up-down',
    class: '-mx-2.5',

    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc')
  })
}


watch([debouncedSearch, selectedPerPage, sorting], () => {
  currentPage.value = 1
}, { deep: true })


const columns: TableColumn<Post>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) => renderSortableHeader('#', column),
  },
  {
    accessorKey: 'user.name',
    id: 'author',
    header: ({ column }) => renderSortableHeader('Автор', column),
  },
  {
    accessorKey: 'category.title',
    header: 'Категорія',
    enableSorting: false
  },
  {
    accessorKey: 'title',
    header: 'Заголовок',
    enableSorting: false
  },
  {
    accessorKey: 'published_at',
    header: ({ column }) => renderSortableHeader('Дата публікації', column),
  },
  {
    id: 'actions',
    header: 'Дії',
    enableSorting: false,
    cell: ({ row }) => {
      const postId = row.original.id
      return h('div', { class: 'flex items-center gap-2' }, [
        h(UButton, {
          to: `/BlogPosts/${postId}`,
          target: '_blank',
          color: 'gray',
          variant: 'soft',
          size: 'xs',
          icon: 'i-heroicons-eye',
          label: 'View'
        }),
        h(UButton, {
          to: `/admin/blog/posts/${postId}/edit`,
          color: 'primary',
          variant: 'soft',
          size: 'xs',
          icon: 'i-heroicons-pencil-square',
          label: 'Edit'
        })
      ])
    }
  }
]
</script>
