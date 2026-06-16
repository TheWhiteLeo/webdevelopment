<template>
  <UCard>
    <UForm
      :schema="schema"
      :state="localCategory"
      @submit="onSubmit"
      class="space-y-6"
    >
      <template v-if="isEditable">
        <UFormField name="title" label="Назва категорії">
          <UInput v-model="localCategory.title" placeholder="Введіть назву..." />
        </UFormField>

        <UFormField name="parent_id" label="Батьківська категорія">
          <USelectMenu
            v-model="localCategory.parent_id"
            :items="parentCategories"
            value-key="id"
            placeholder="Оберіть батьківську категорію"
            :loading="loadingDependencies"
          />
        </UFormField>

        <div class="flex justify-end pt-4">
          <UButton
            type="submit"
            color="success"
            icon="i-lucide-check"
            label="Зберегти зміни"
            :loading="isSubmitting"
          />
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
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { z } from 'zod'
import type { Category } from '~/types/Category'

const props = withDefaults(defineProps<{
  category: Category
  isEditable?: boolean
  parentCategories?: { id: number | string; label: string }[]
  loadingDependencies?: boolean
  isSubmitting?: boolean
}>(), {
  isEditable: false,
  parentCategories: () => [],
  loadingDependencies: false,
  isSubmitting: false
})

const emit = defineEmits<{
  (e: 'save', payload: Category): void
}>()

const localCategory = ref<Category>({ ...props.category })

// 1. Оновлюємо локальну копію, якщо дані прийшли з сервера після збереження
watch(() => props.category, (newCategory) => {
  localCategory.value = { ...newCategory }
}, { deep: true })


const schema = z.object({
  title: z.string({ required_error: 'Назва обов\'язкова' })
    .min(5, 'Мінімум 5 символів')
    .max(200, 'Максимум 200 символів'),

  parent_id: z.union([z.string(), z.number()])
    .refine(val => val !== '' && val !== null && val !== undefined, 'Оберіть батьківську категорію')
    .refine(val => Number.isInteger(Number(val)), 'Ідентифікатор має бути цілим числом')
})

const onSubmit = () => {
  emit('save', localCategory.value)
}
</script>
