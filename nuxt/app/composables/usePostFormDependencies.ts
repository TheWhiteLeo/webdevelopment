import { computed, watch, type Ref } from 'vue'
import type { Category } from '~/types/Category'
import type { LaravelResponse } from '~/types/LaravelPaginatedResponse'

export function usePostFormDependencies(isEditable: Ref<boolean>) {
  const { data: categoriesData, pending: loadingCategories, execute: fetchCategories } = useLaravelFetch<LaravelResponse<Category[]>>('/admin/blog/categories', {
    immediate: isEditable.value,
    query: { nopaginate: true }
  })

  watch(isEditable, async (isNowEditable) => {
    if (isNowEditable) {
      if (!categoriesData.value) await fetchCategories()
    }
  })

  const categories = computed(() => {
    if (!categoriesData.value) return []
    const rawData = Array.isArray(categoriesData.value)
      ? categoriesData.value
      : (categoriesData.value as LaravelResponse<Category[]>).data
    return rawData.map((cat: Category) => ({ ...cat, label: cat.title }))
  })

  const loadingDependencies = computed(() => loadingCategories.value)

  return {
    categories,
    loadingDependencies
  }
}
