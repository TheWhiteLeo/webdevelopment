<template>
  <div class="max-w-2xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-semibold">Створення категорії</h2>
      <UButton label="Назад" color="gray" variant="ghost" icon="i-lucide-arrow-left" @click="navigateTo('/admin/categories')" />
    </div>

    <UCard>
      <UForm :state="form" @submit="onSubmit" class="space-y-6">
        <UFormField name="title" label="Назва категорії">
          <UInput v-model="form.title" placeholder="Введіть назву..." />
        </UFormField>

        <UFormField name="parent_id" label="Батьківська категорія (опціонально)">
          <USelectMenu
            v-model="form.parent_id"
            :items="categoriesList"
            value-key="id"
            placeholder="Оберіть батьківську категорію"
            :loading="loadingCategories"
            clearable
          />
        </UFormField>

        <div class="flex justify-end">
          <UButton type="submit" color="success" icon="i-lucide-check" label="Створити" :loading="isSubmitting" />
        </div>
      </UForm>
    </UCard>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'

const form = reactive({
  title: '',
  parent_id: null as string | number | null
})
const isSubmitting = ref(false)
const config = useRuntimeConfig()

const { data: categoriesResponse, pending: loadingCategories } = await useLaravelFetch('/admin/blog/categories', {
  query: { nopaginate: true }
})

// Форматуємо дані для USelectMenu
const categoriesList = computed(() => {
  if (!categoriesResponse.value) return []
  const rawData = Array.isArray(categoriesResponse.value)
    ? categoriesResponse.value
    : categoriesResponse.value.data

  return rawData.map((cat: any) => ({
    id: cat.id,
    label: cat.title
  }))
})

const onSubmit = async () => {
  if (!form.title) return alert('Назва обов\'язкова')

  isSubmitting.value = true
  try {
    await $fetch('/admin/blog/categories', {
      baseURL: config.public.laravelUrl,
      method: 'POST',
      body: form,
      headers: { Accept: 'application/json' }
    })
    navigateTo('/admin/categories')
  } catch (error) {
    console.error(error)
    alert('Помилка збереження категорії')
  } finally {
    isSubmitting.value = false
  }
}
</script>
