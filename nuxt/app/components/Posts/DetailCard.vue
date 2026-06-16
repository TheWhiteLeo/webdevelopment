<template>
  <UCard>
    <UForm
      :schema="schema"
      :state="localPost"
      @submit="onSubmit"
      class="space-y-6"
    >
      <template v-if="isEditable">
        <div class="flex flex-wrap items-start gap-4">
          <UFormField name="category_id" label="Категорія" class="w-52">
            <USelectMenu
              v-model="localPost.category_id"
              :items="categories"
              value-key="id"
              placeholder="Оберіть категорію"
              :loading="loadingDependencies"
            />
          </UFormField>

          <UFormField name="is_published" label="Статус" class="w-40">
            <USelectMenu
              v-model="localPost.is_published"
              :items="[
                { id: true, label: 'Опубліковано' },
                { id: false, label: 'Чернетка' }
              ]"
              value-key="id"
            />
          </UFormField>
        </div>

        <UFormField name="title" label="Заголовок поста">
          <UInput
            v-model="localPost.title"
            size="lg"
            placeholder="Введіть заголовок..."
            class="w-full"
          />
        </UFormField>

        <UFormField name="content_raw" label="Текст поста">
          <UTextarea
            v-model="localPost.content_raw"
            :rows="12"
            autoresize
            resize
            placeholder="Напишіть щось цікаве..."
            class="w-full"
          />
        </UFormField>

        <div class="flex justify-end pt-4">
          <UButton
            type="submit"
            icon="i-lucide-check"
            color="success"
            label="Зберегти зміни"
          />
        </div>
      </template>

      <template v-else>
        <div class="space-y-4">
          <div class="flex flex-wrap items-center gap-4">
            <UBadge color="gray" variant="solid" size="md">
              {{ post.category_title || 'Без категорії' }}
            </UBadge>

            <UBadge :color="post.is_published ? 'green' : 'amber'" variant="subtle" size="md">
              {{ post.is_published ? 'Опубліковано' : 'Чернетка' }}
            </UBadge>
          </div>

          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ post.title }}
          </h1>

          <div class="flex items-center gap-6 pt-2 border-t border-gray-100 dark:border-gray-800 text-sm text-gray-500">
            <div class="flex items-center gap-2">
              <UAvatar :src="post.user?.profile_photo_url" :alt="post.author_name" size="xs" />
              <span class="font-medium text-gray-700 dark:text-gray-300">{{ post.author_name }}</span>
            </div>

            <div class="flex items-center gap-1 self-end pb-1">
              <UIcon name="i-heroicons-calendar" class="w-4 h-4 text-gray-400" />
              <span>{{ post.published_at || 'Не опубліковано' }}</span>
            </div>
          </div>
        </div>
        <div class="prose">{{ post.content_raw }}</div>
      </template>
    </UForm>
  </UCard>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { z } from 'zod'
import type { PostDetail } from '~/types/PostIndex'

const postSchema = z.object({
  title: z.string({ required_error: 'Назва обов\'язкова' })
  .min(5, 'Мінімум 5 символів')
  .max(200, 'Максимум 200 символів'),
  content_raw: z.string().min(5, 'Текст поста обов\'язковий').max(10000, 'Максимум 10000 символів'),
  category_id: z.union([z.string(), z.number()]).refine(val => val !== '' && val !== null && val !== undefined, 'Оберіть категорію'),
  is_published: z.boolean()
})

const props = withDefaults(defineProps<{
  post: PostDetail
  categories?: { id: number | string; label: string }[]
  isEditable?: boolean
  loadingDependencies?: boolean
}>(), {
  isEditable: false,
  categories: () => [],
  loadingDependencies: false
})

const emit = defineEmits<{
  (e: 'save', payload: PostDetail): void
}>()

const localPost = ref({ ...props.post })

watch(() => props.post, (newPost) => {
  localPost.value = { ...newPost }
}, { deep: true })

const onSubmit = () => {
  emit('save', localPost.value)
}

const schema = postSchema
</script>
