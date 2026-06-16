<template>
  <div class="w-full space-y-4 pb-4">
    <div class="flex items-center justify-between gap-4 px-4 py-3.5 border-b border-accented">
      <UInput
        v-model="search"
        class="max-w-sm w-64"
        :placeholder="searchPlaceholder"
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
      :columns="columns"
      :data="data"
      :loading="pending"
      class="flex-1"
      @select="onRowSelect"
    >
      <template v-for="(_, name) in $slots" #[name]="slotData">
        <slot :name="name" v-bind="slotData" />
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

<script setup lang="ts" generic="T">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  data: T[]
  columns: any[]
  total: number
  pending?: boolean
  searchPlaceholder?: string
}>(), {
  pending: false,
  searchPlaceholder: 'Пошук...'
})

const search = defineModel<string>('search', { default: '' })
const page = defineModel<number>('page', { default: 1 })
const itemsPerPage = defineModel<number>('itemsPerPage', { default: 25 })
const sorting = defineModel<{ id: string; desc: boolean }[]>('sorting', {
  default: () => [{ id: 'id', desc: true }]
})

const emit = defineEmits<{
  (e: 'row-select', row: T): void
}>()

const perPageOptions = [5, 10, 20, 25, 50, 100]

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

function onRowSelect(_e: Event, row: { original: T }) {
  emit('row-select', row.original)
}
</script>
