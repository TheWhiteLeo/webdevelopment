<template>
  <div class="max-w-4xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-semibold">Створення нового поста</h2>
      <UButton
        label="Скасувати"
        color="gray"
        variant="ghost"
        icon="i-lucide-arrow-left"
        @click="navigateTo('/admin/blog-posts')"
      />
    </div>

    <PostsDetailCard
      :post="newPost"
      :is-editable="true"
      :categories="categories"
      :loading-dependencies="loadingDependencies"
      @save="handleCreate"
    />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { PostDetail } from '~/types/PostIndex'

// Вмикаємо режим редагування одразу, щоб завантажились категорії
const editMode = ref(true)
const { categories, loadingDependencies } = usePostFormDependencies(editMode)

// Порожній шаблон поста
const newPost = ref<PostDetail>({
  id: '',
  title: '',
  slug: '',
  is_published: false,
  published_at: null,
  category_title: '',
  author_name: '',
  category_id: '',
  content_raw: '',
  content_html: ''
})

const handleCreate = async (postData: PostDetail) => {
  const config = useRuntimeConfig()
  try {
    await $fetch('/admin/blog/posts', {
      baseURL: config.public.laravelUrl,
      method: 'POST',
      body: {
        title: postData.title,
        content_raw: postData.content_raw,
        category_id: postData.category_id,
        is_published: postData.is_published
      },
      headers: { Accept: 'application/json' }
    })

    // Повертаємось до списку після успішного створення
    navigateTo('/admin/blog-posts')
  } catch (error) {
    console.error('Помилка створення поста:', error)
    alert('Не вдалося створити пост. Перевірте консоль.')
  }
}
</script>
