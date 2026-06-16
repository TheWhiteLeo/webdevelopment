<template>
  <div class="max-w-2xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center">
      <div class="flex items-center gap-4">
        <UButton color="gray" variant="ghost" icon="i-lucide-arrow-left" @click="navigateTo('/admin/categories')" />
        <h2 class="text-2xl font-semibold">{{ editMode ? 'Редагування категорії' : 'Перегляд категорії' }}</h2>
      </div>
      <USwitch v-model="editMode" label="Режим редагування" />
    </div>

    <div v-if="pendingCategory" class="flex justify-center py-12">
      <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-gray-400" />
    </div>

    <CategoriesDetailCard
      v-else-if="category"
      :category="category"
      :is-editable="editMode"
      :parent-categories="filteredCategoriesList"
      :loading-dependencies="loadingParentCategories"
      :is-submitting="isSubmitting"
      @save="handleSave"
    />

    <div v-else class="text-center py-12 text-gray-500">
      Категорію не знайдено.
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { Category } from '~/types/Category'

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()

// Стан сторінки
const editMode = ref(route.query.edit === 'true')
const isSubmitting = ref(false)

// 1. Отримання даних поточної категорії
const { data: categoryResponse, pending: pendingCategory, refresh: refreshCategory } = await useLaravelFetch<any>(`/admin/blog/categories/${route.params.id}`)

const category = computed<Category | null>(() => {
  if (!categoryResponse.value) return null
  const rawData = categoryResponse.value.data ? categoryResponse.value.data : categoryResponse.value
  return Array.isArray(rawData) ? (rawData[0] || null) : rawData
})

// 2. Отримання залежностей (категорії для випадаючого списку)
const { data: parentCategoriesResponse, pending: loadingParentCategories, execute: fetchParents } = useLaravelFetch('/admin/blog/categories', {
  immediate: editMode.value, // Завантажуємо одразу, якщо зайшли через ?edit=true
  query: { nopaginate: true }
})

// Підтягуємо залежності, якщо користувач увімкнув тумблер редагування
watch(editMode, async (isNowEditable) => {
  if (isNowEditable && !parentCategoriesResponse.value) {
    await fetchParents()
  }
})

// Форматуємо дані для селекта, прибираючи саму себе
const filteredCategoriesList = computed(() => {
  if (!parentCategoriesResponse.value) return []
  const rawData = Array.isArray(parentCategoriesResponse.value)
    ? parentCategoriesResponse.value
    : (parentCategoriesResponse.value as any).data

  return rawData
    .filter((cat: any) => String(cat.id) !== String(route.params.id))
    .map((cat: any) => ({ id: cat.id, label: cat.title }))
})

// 3. Збереження даних (обробник події @save від компонента)
const handleSave = async (updatedCategory: Category) => {
  isSubmitting.value = true
  try {
    await $fetch(`/admin/blog/categories/${route.params.id}`, {
      baseURL: config.public.laravelUrl,
      method: 'PUT',
      body: {
        title: updatedCategory.title,
        parent_id: updatedCategory.parent_id
      },
      headers: { Accept: 'application/json' }
    })

    await refreshCategory() // Оновлюємо дані на сторінці після збереження
    editMode.value = false // Вимикаємо режим редагування

    // Очищуємо URL
    router.replace({ query: {} })
  } catch (error) {
    console.error(error)
    alert('Помилка оновлення категорії')
  } finally {
    isSubmitting.value = false
  }
}
</script>
