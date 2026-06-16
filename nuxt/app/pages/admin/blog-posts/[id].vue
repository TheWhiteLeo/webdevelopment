<template>
  <div class="max-w-4xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-semibold">Управління постом</h2>
      <USwitch v-model="editMode" label="Режим редагування" />
    </div>

    <div v-if="pendingPost" class="flex justify-center py-12">
      <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-gray-400" />
    </div>

    <PostsDetailCard
      v-else-if="post"
      :post="post"
      :is-editable="editMode"
      :categories="categories"
      :loading-dependencies="loadingDependencies"
      @save="handleSave"
    />

    <div v-else class="text-center py-12 text-gray-500">
      Не вдалося завантажити дані поста. Можливо, він був видалений або виникла помилка сервера.
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import type { PostDetail } from '~/types/PostIndex'
import type { LaravelResponse } from '~/types/LaravelPaginatedResponse'

const route = useRoute()
const editMode = ref(route.query.edit === 'true')

// 1. Отримуємо сам пост (логіка рівня сторінки)
const { data: postResponse, refresh: refreshPost } = await useLaravelFetch<LaravelResponse<PostDetail>>(`/admin/blog/posts/${route.params.id}`)
const post = computed(() => postResponse.value?.data)

// 2. Підключаємо довідники для форми
const { categories, loadingDependencies } = usePostFormDependencies(editMode)

// 3. Логіка збереження
const handleSave = async (updatedPost: PostDetail) => {
  const config = useRuntimeConfig()
  try {
    await $fetch(`/admin/blog/posts/${updatedPost.id}`, {
      baseURL: config.public.laravelUrl,
      method: 'PUT',
      body: updatedPost,
      headers: { Accept: 'application/json' }
    })
    // Оновлюємо дані поста та виходимо з режиму редагування
    await refreshPost()
    editMode.value = false
  } catch (error) {
    console.error('Помилка збереження:', error)
  }
}
</script>
