<template>
  <div class="max-w-7xl mx-auto py-8 space-y-6">
    <PostsAppHeader />
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold">Список постів</h1>
      <UButton
        label="Створити пост"
        icon="i-lucide-plus"
        color="primary"
        @click="navigateTo('/admin/blog-posts/create')"
      />
    </div>

    <PostsTableComponent
      v-model:search="globalFilter"
      v-model:page="currentPage"
      v-model:items-per-page="selectedPerPage"
      v-model:sort="sorting"
      :posts="currentPagePosts"
      :total="total"
      :pending="pending"
      :is-admin="true"
      row-link-prefix="/admin/blog-posts"
      @delete="handleDeletePost"
    />
  </div>
</template>

<script setup lang="ts">

const {
  currentPage,
  globalFilter,
  selectedPerPage,
  sorting,
  pending,
  currentPagePosts,
  total,
  deletePost
} = useBlogPosts(true)


const handleDeletePost = async (id: string | number) => {
  await deletePost(id)
}
</script>
