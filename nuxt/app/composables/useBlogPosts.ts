import { ref, computed, watch } from 'vue'
import { refDebounced } from '@vueuse/core'
import type { PostIndex } from '~/types/PostIndex'
import type { LaravelPaginatedResponse } from '~/types/LaravelPaginatedResponse'

export function useBlogPosts(isAdmin = false) {
  const currentPage = ref(1)
  const globalFilter = ref('')
  const debouncedSearch = refDebounced(globalFilter, 500)
  const selectedPerPage = ref(25)

  const sorting = ref<{ id: string; desc: boolean }[]>([{ id: 'id', desc: true }])

  // Реактивні параметри для Laravel API
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

  // Підтягування даних
  const endpoint = isAdmin ? '/admin/blog/posts' : '/blog/posts'
  const { data: response, pending, refresh } = useLaravelFetch<LaravelPaginatedResponse<PostIndex>>(endpoint, {
    query: apiQuery
  })

  const currentPagePosts = computed<PostIndex[]>(() => response.value?.data || [])
  const total = computed<number>(() => response.value?.meta.total || 0)
  const perPage = computed<number>(() => response.value?.meta.per_page || 25)

  // Скидаємо сторінку на першу при зміні фільтрів чи сортування
  watch([debouncedSearch, selectedPerPage, sorting], () => {
    currentPage.value = 1
  }, { deep: true })

  // Логіка видалення (тільки для адміна)
  const deletePost = isAdmin
    ? async (id: string | number) => {
        try {
          await useLaravelFetch(`/admin/blog/posts/${id}`, { method: 'DELETE' })
          await refresh()
        } catch (error) {
          console.error('Помилка видалення', error)
        }
      }
    : undefined

  return {
    currentPage,
    globalFilter,
    selectedPerPage,
    sorting,
    pending,
    currentPagePosts,
    total,
    perPage,
    deletePost
  }
}
