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

    <UCard v-else-if="category">
      <UForm :state="form" @submit="onSubmit" class="space-y-6">

        <template v-if="editMode">
          <UFormField name="title" label="Назва категорії">
            <UInput v-model="form.title" placeholder="Введіть назву..." />
          </UFormField>

          <UFormField name="parent_id" label="Батьківська категорія">
            <USelectMenu
              v-model="form.parent_id"
              :items="filteredCategoriesList"
              value-key="id"
              placeholder="Оберіть батьківську категорію"
              :loading="loadingParentCategories"
              clearable
            />
          </UFormField>

          <div class="flex justify-end pt-4">
            <UButton type="submit" color="success" icon="i-lucide-check" label="Зберегти зміни" :loading="isSubmitting" />
          </div>
        </template>

        <template v-else>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">ID категорії</label>
              <div class="text-gray-900 font-mono">{{ category.id }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 mb-1">Назва</label>
              <div class="text-xl font-medium text-gray-900">{{ category.title }}</div>
            </div>

            <div v-if="category.parent_category">
              <label class="block text-sm font-medium text-gray-500 mb-1">Батьківська категорія</label>
              <UBadge color="primary" variant="subtle">{{ category.parent_category }}</UBadge>
            </div>
          </div>
        </template>

      </UForm>
    </UCard>

    <div v-else class="text-center py-12 text-gray-500">
      Категорію не знайдено.
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import type { Category } from '~/types/Category'

const route = useRoute()
const config = useRuntimeConfig()

// Перевіряємо чи перейшли ми на сторінку одразу з наміром редагувати
const editMode = ref(route.query.edit === 'true')
const isSubmitting = ref(false)

const { data: categoryResponse, pending: pendingCategory, refresh: refreshCategory } = await useLaravelFetch<any>(`/admin/blog/categories/${route.params.id}`)

const category = computed<Category | null>(() => {
  if (!categoryResponse.value) return null

  const rawData = categoryResponse.value.data ? categoryResponse.value.data : categoryResponse.value

  if (Array.isArray(rawData)) {
    return rawData[0] || null
  }

  return rawData
})

const form = reactive({
  title: '',
  parent_id: null as string | number | null
})

watch(category, (newVal) => {
  if (newVal) {
    form.title = newVal.title
    form.parent_id = newVal.parent_id || null
  }
}, { immediate: true })

// 2. Отримуємо список для випадайки (тільки якщо ввімкнено режим редагування)
const { data: parentCategoriesResponse, pending: loadingParentCategories, execute: fetchParents } = useLaravelFetch('/admin/blog/categories', {
  immediate: editMode.value,
  query: { nopaginate: true }
})

watch(editMode, async (isNowEditable) => {
  if (isNowEditable && !parentCategoriesResponse.value) {
    await fetchParents()
  }
})

// Форматуємо список та прибираємо поточну категорію (щоб вона не стала батьківською для самої себе)
const filteredCategoriesList = computed(() => {
  if (!parentCategoriesResponse.value) return []
  const rawData = Array.isArray(parentCategoriesResponse.value)
    ? parentCategoriesResponse.value
    : parentCategoriesResponse.value.data

  return rawData
    .filter((cat: any) => String(cat.id) !== String(route.params.id))
    .map((cat: any) => ({
      id: cat.id,
      label: cat.title
    }))
})

// 3. Збереження
const onSubmit = async () => {
  if (!form.title) return alert('Назва обов\'язкова')

  isSubmitting.value = true
  try {
    await $fetch(`/admin/blog/categories/${route.params.id}`, {
      baseURL: config.public.laravelUrl,
      method: 'PUT',
      body: form,
      headers: { Accept: 'application/json' }
    })

    await refreshCategory()
    editMode.value = false

    // Прибираємо query параметр ?edit=true з URL
    const router = useRouter()
    router.replace({ query: {} })
  } catch (error) {
    console.error(error)
    alert('Помилка оновлення категорії')
  } finally {
    isSubmitting.value = false
  }
}
</script>
