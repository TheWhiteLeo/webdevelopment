import { ref, computed, watch } from 'vue'
import { refDebounced } from '@vueuse/core'
import type { Category } from '~/types/Category'
import type { LaravelPaginatedResponse } from '~/types/LaravelPaginatedResponse'

export function useBlogCategories() {
  const currentPage = ref(1)
  const globalFilter = ref('')
  const debouncedSearch = refDebounced(globalFilter, 500)
  const selectedPerPage = ref(25)

  const sorting = ref<{ id: string; desc: boolean }[]>([{ id: 'id', desc: true }])

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

  const endpoint = '/admin/blog/categories'
  const { data: response, pending, refresh } = useLaravelFetch<LaravelPaginatedResponse<Category>>(endpoint, {
    query: apiQuery
  })

  const categories = computed<Category[]>(() => response.value?.data || [])
  const total = computed<number>(() => response.value?.meta.total || 0)
  const perPage = computed<number>(() => response.value?.meta.per_page)

  watch([debouncedSearch, selectedPerPage, sorting], () => {
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
    sorting,
    pending,
    categories,
    total,
    perPage,
    deleteCategory
  }
}
