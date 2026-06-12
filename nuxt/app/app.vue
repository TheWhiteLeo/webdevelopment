<template>
  <div class="min-h-screen bg-gray-50 text-gray-700 font-sans">
    <NuxtPage />
  </div>
</template>

<script setup>
// Цей код гарантує, що застосунок і всі компоненти Nuxt UI будуть світлими
const colorMode = useColorMode()
colorMode.preference = 'light'
</script>
