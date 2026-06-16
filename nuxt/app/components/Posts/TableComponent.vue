<template>
  <div class="w-full space-y-4 pb-4">
    <div class="flex items-center justify-between gap-4 px-4 py-3.5 border-b border-accented">
      <UInput
        v-model="search"
        class="max-w-sm w-64"
        placeholder="Пошук по ID, Автору, Заголовку..."
        icon="i-heroicons-magnifying-glass-20-solid"
      />

      <UDropdownMenu
        :items="perPageItems"
        :content="{ align: 'end' }"
      >
        <UButton
          :label="`Показувати: ${itemsPerPage}`"
          color="neutral"
          variant="outline"
          trailing-icon="i-lucide-chevron-down"
          aria-label="Вибір кількості записів на сторінку"
        />
      </UDropdownMenu>
    </div>

    <UTable
      ref="table"
      :columns="columns as any"
      :data="posts"
      sort-mode="manual"
      v-model:sort="sort"
      :loading="pending"
      class="flex-1"
      @select="onRowSelect"
    >
      <template v-if="isAdmin" #actions-cell="{ row }">
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

    <div class="flex justify-end border-t border-default pt-4 px-4">
      <UPagination
        v-model:page="page"
        :items-per-page="itemsPerPage"
        :total="total"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { resolveComponent, computed, h } from 'vue'
import type { TableColumn, DropdownMenuItem } from '@nuxt/ui'
import type { PostIndex } from '~/types/PostIndex'

const UButton = resolveComponent('UButton')

const props = withDefaults(defineProps<{
  posts: PostIndex[]
  total: number
  pending: boolean
  isAdmin?: boolean
  rowLinkPrefix?: string
}>(), {
  isAdmin: false,
  rowLinkPrefix: '/blog-posts'
})

const search = defineModel<string>('search', { default: '' })
const page = defineModel<number>('page', { default: 1 })
const itemsPerPage = defineModel<number>('itemsPerPage', { default: 25 })
const sort = defineModel<{ id: string; desc: boolean }[]>('sort', {
  default: () => [{ id: 'id', desc: true }]
})

const emit = defineEmits<{
  (e: 'delete', id: string | number): void
}>()

const perPageOptions = [10, 20, 25, 50, 100]

const perPageItems = computed(() => {
  return perPageOptions.map(value => ({
    label: `${value} записів`,
    type: 'checkbox' as const,
    checked: itemsPerPage.value === value,
    onUpdateChecked(checked: boolean) {
      if (checked) {
        itemsPerPage.value = value
      }
    }
  }))
})

const renderSortableHeader = (label: string, columnId: string) => {
  const currentSort = sort.value[0]
  const isCurrentColumn = currentSort?.id === columnId

  let icon = 'i-lucide-arrow-up-down'
  if (isCurrentColumn) {
    icon = currentSort.desc
      ? 'i-lucide-arrow-down-wide-narrow'
      : 'i-lucide-arrow-up-narrow-wide'
  }

  return h(UButton, {
    color: 'neutral',
    variant: 'ghost',
    label: label,
    icon: icon,
    class: '-mx-2.5',
    onClick: () => {
      if (isCurrentColumn) {
        sort.value = [{ id: columnId, desc: !currentSort.desc }]
      } else {
        sort.value = [{ id: columnId, desc: false }]
      }
    }
  })
}

const baseColumns: TableColumn<PostIndex>[] = [
  { accessorKey: 'id', header: () => renderSortableHeader('#', 'id') },
  { accessorKey: 'author_name', header: () => renderSortableHeader('Автор', 'author_name') },
  { accessorKey: 'category_title', header: 'Категорія' },
  { accessorKey: 'title', header: 'Заголовок' },
  { accessorKey: 'published_at', header: () => renderSortableHeader('Дата публікації', 'published_at') }
]

const columns = computed(() => {
  if (props.isAdmin) {
    return [...baseColumns, { id: 'actions' } as TableColumn<PostIndex>]
  }
  return baseColumns
})

function onRowSelect(_e: Event, row: { original: PostIndex }) {
  navigateTo(`${props.rowLinkPrefix}/${row.original.id}`)
}

function getDropdownActions(post: PostIndex): DropdownMenuItem[][] {
  return [
    [
      {
        label: 'Редагувати',
        icon: 'i-lucide-edit',
        onSelect: () => navigateTo({ path: `/admin/blog-posts/${post.id}`, query: { edit: 'true' } })
      }
    ],
    [
      {
        label: 'Видалити',
        icon: 'i-lucide-trash',
        color: 'error',
        onSelect: () => {
          if (confirm(`Ви впевнені, що хочете видалити пост "${post.title}"?`)) {
            emit('delete', post.id)
          }
        }
      }
    ]
  ]
}
</script>
