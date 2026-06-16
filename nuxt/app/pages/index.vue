<template>
  <div class="min-h-screen bg-gray-50 text-gray-700 font-sans">
    <PostsAppHeader />
    <main class="py-6">
      <NuxtPage />
    </main>
  </div>
</template>

<script setup>
const colorMode = useColorMode()
colorMode.preference = 'light'
</script>
