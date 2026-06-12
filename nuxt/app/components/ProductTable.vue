<template>
  <div class="relative bg-white w-full max-w-6xl rounded-xl shadow-lg border border-gray-100 transition-colors duration-300 overflow-hidden flex flex-col">
    <div class="p-6">
      <div class="flex flex-col sm:flex-row items-start justify-between gap-4 mb-6">
        <UInput
          v-model="searchQuery"
          icon="i-heroicons-magnifying-glass-20-solid"
          placeholder="Шукати продукти..."
          class="w-full sm:w-72"
          color="gray"
          variant="outline"
        />
      </div>

      <div class="border border-gray-200 rounded-lg overflow-x-auto w-full">
        <UTable
          v-model:sorting="sorting"
          :data="paginatedRows"
          :columns="columns"
          class="min-w-full"
          :loading="pending"
        >
          <template #thumbnail-cell="{ row }">
            <img
              :src="row.original.thumbnail"
              alt="Product"
              class="w-25 h-25 min-w-25 object-cover rounded-md shadow-sm"
            />
          </template>

          <template #title-cell="{ row }">
            <span class="font-medium text-gray-900">{{ row.original.title }}</span>
          </template>

          <template #description-cell="{ row }">
            <div class="whitespace-normal min-w-62.5 max-w-87.5 text-gray-600 text-sm leading-relaxed">
              {{ row.original.description }}
            </div>
          </template>

          <template #price-cell="{ row }">
            <span class="font-semibold text-gray-900">${{ row.original.price }}</span>
          </template>

          <template #rating-cell="{ row }">
              <span :class="row.original.rating < 4.5 ? 'text-red-500' : 'text-green-500'" class="font-bold">
                {{ row.original.rating }}
              </span>
          </template>
        </UTable>
      </div>

      <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-sm font-medium text-gray-500">
          Всього знайдено: {{ filteredAndSortedRows.length }}
        </div>

        <UPagination
          v-model:page="page"
          :items-per-page="pageCount"
          :total="filteredAndSortedRows.length"
          color="gray"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

const { data: products, pending } = await useFetch('https://dummyjson.com/products', {
  transform: (response) => {
    return response.products.map(product => ({
      id: product.id,
      thumbnail: product.thumbnail,
      title: product.title,
      description: product.description,
      price: product.price,
      rating: product.rating,
      brand: product.brand || 'Без бренду',
      category: product.category
    }))
  }
})

const searchQuery = ref('')
const page = ref(1)
const pageCount = ref(5)

const sorting = ref([{ id: 'title', desc: false }])

const columns = [
  { accessorKey: 'thumbnail', header: 'Фото', enableSorting: false },
  { accessorKey: 'title', header: 'Назва', enableSorting: true },
  { accessorKey: 'description', header: 'Опис', enableSorting: false },
  { accessorKey: 'price', header: 'Ціна', enableSorting: true },
  { accessorKey: 'rating', header: 'Оцінка', enableSorting: true },
  { accessorKey: 'brand', header: 'Бренд', enableSorting: true },
  { accessorKey: 'category', header: 'Категорія', enableSorting: true }
]

const filteredAndSortedRows = computed(() => {
  let rows = products.value || []

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    rows = rows.filter(row => {
      return Object.values(row).some(value =>
        String(value).toLowerCase().includes(query)
      )
    })
  }

  if (sorting.value.length > 0) {
    const { id, desc } = sorting.value[0]
    rows = [...rows].sort((a, b) => {
      if (a[id] < b[id]) return desc ? 1 : -1
      if (a[id] > b[id]) return desc ? -1 : 1
      return 0
    })
  }

  return rows
})

const paginatedRows = computed(() => {
  const start = (page.value - 1) * pageCount.value
  const end = start + pageCount.value
  return filteredAndSortedRows.value.slice(start, end)
})

watch(searchQuery, () => {
  page.value = 1
})
</script>
