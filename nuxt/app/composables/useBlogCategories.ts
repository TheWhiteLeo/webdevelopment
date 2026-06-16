import { ref, computed, watch } from 'vue'
import { refDebounced } from '@vueuse/core'
import type { Category } from '~/types/Category'
import type { LaravelPaginatedResponse } from '~/types/LaravelPaginatedResponse'

export function useBlogCategories() {
  const currentPage = ref(1)
  const globalFilter = ref('')
  const debouncedSearch = refDebounced(globalFilter, 500)
  const selectedPerPage = ref(25)

  const apiQuery = computed(() => ({
    page: currentPage.value,
    per_page: selectedPerPage.value,
    search: debouncedSearch.value,
    nopaginate: true
  }))

  const { data: response, pending, refresh } = useLaravelFetch<LaravelPaginatedResponse<Category>>('/admin/blog/categories', {
    query: apiQuery
  })

  const categories = computed<Category[]>(() => response.value?.data || [])
  const total = computed<number>(() => response.value?.total || 0)

  watch([debouncedSearch, selectedPerPage], () => {
    currentPage.value = 1
  }, { deep: true })

  const deleteCategory = async (id: string | number) => {
    try {
      await useLaravelFetch(`/admin/blog/categories/${id}`, { method: 'DELETE' })
      await refresh()
    } catch (error) {
      console.error('Помилка видалення', error)
    }
  }

  return {
    currentPage,
    globalFilter,
    selectedPerPage,
    pending,
    categories,
    total,
    deleteCategory
  }
}
