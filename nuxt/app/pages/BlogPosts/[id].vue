<template>
  <div class="container mx-auto p-6 max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
      <UButton
        to="/BlogPosts"
        icon="i-heroicons-arrow-left"
        color="gray"
        variant="ghost"
        label="Назад до списку"
      />

      <UButton
        :to="`#`"
        icon="i-heroicons-pencil-square"
        color="primary"
        label="Редагувати"
      />
    </div>

    <div v-if="pending" class="flex flex-col space-y-4">
      <USkeleton class="h-8 w-[250px]" />
      <USkeleton class="h-4 w-[350px]" />
      <USkeleton class="h-40 w-full" />
    </div>
    <UAlert
      v-else-if="error"
      icon="i-heroicons-exclamation-triangle"
      color="red"
      variant="soft"
      title="Помилка завантаження"
      description="Не вдалося знайти цей пост або сервер повернув помилку."
    />
    <UCard v-else-if="post">
      <template #header>
        <div class="space-y-3">
          <div class="flex items-center gap-2 text-xs">
            <UBadge color="gray" variant="solid">
              {{ post.category?.title || 'Без категорії' }}
            </UBadge>

            <UBadge :color="post.is_published ? 'green' : 'amber'" variant="subtle">
              {{ post.is_published ? 'Опубліковано' : 'Чернетка' }}
            </UBadge>
          </div>

          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ post.title }}
          </h1>

          <div class="flex items-center gap-4 pt-2 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
              <UAvatar
                :src="post.user?.profile_photo_url"
                :alt="post.user?.name"
                size="xs"
              />
              <span class="font-medium text-gray-700 dark:text-gray-300">
                {{ post.user?.name || 'Невідомий автор' }}
              </span>
            </div>

            <div class="flex items-center gap-1">
              <UIcon name="i-heroicons-calendar" class="w-4 h-4 text-gray-400" />
              <span>{{ post.published_at || 'Дата відсутня' }}</span>
            </div>
          </div>
        </div>
      </template>

      <div class="prose max-w-none dark:prose-invert text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
        {{ post.content_html || 'Текст поста відсутній.' }}
      </div>
    </UCard>
  </div>
</template>

<script setup lang="ts">
import type { Post } from '~/types/Post'

const route = useRoute()

const postId = route.params.id

const { data: responseData, pending, error } = await useLaravelFetch<{ data?: Post } | Post>(`/blog/posts/${postId}`)

const post = computed(() => {
  if (!responseData.value) return null

  return responseData.value.data ? responseData.value.data : responseData.value
})
</script>
