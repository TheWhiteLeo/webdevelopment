<template>
  <div class="max-w-7xl mx-auto py-8 space-y-6">
    <PostsAppHeader />
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-semibold">Список постів</h1>
    </div>

    <PostsTableComponent
      v-model:search="globalFilter"
      v-model:page="currentPage"
      v-model:items-per-page="selectedPerPage"
      v-model:sort="sorting"
      :posts="currentPagePosts"
      :total="total"
      :pending="pending"
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
  total
} = useBlogPosts()
</script>
