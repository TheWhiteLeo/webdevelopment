<template>
  <div class="max-w-4xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-semibold">Перегляд поста</h2>
    </div>

    <div v-if="pendingPost" class="flex justify-center py-12">
      <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-gray-400" />
    </div>

    <PostsDetailCard
      v-else-if="post"
      :post="post"
      :is-editable="false"
    />

    <div v-else class="text-center py-12 text-gray-500">
      Не вдалося завантажити дані поста. Можливо, він був видалений або виникла помилка сервера.
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import type { PostDetail } from '~/types/PostIndex'
import type {LaravelResponse} from "~/types/LaravelPaginatedResponse";

const route = useRoute()

const { data: postResponse, pending: pendingPost } = useLaravelFetch<LaravelResponse<PostDetail>>(`/blog/posts/${route.params.id}`)

const post = computed<PostDetail | null>(() => {
  if (!postResponse.value) return null
  return postResponse.value.data ? postResponse.value.data : postResponse.value
})
</script>
