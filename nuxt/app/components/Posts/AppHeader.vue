<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">

      <NuxtLink to="/" class="flex items-center gap-2 text-xl font-bold text-gray-800">
        <UIcon name="i-heroicons-cube-transparent" class="w-8 h-8 text-primary-500" />
        <span>Simple Project</span>
      </NuxtLink>

      <nav class="flex items-center gap-6">

        <div class="flex items-center gap-1 border-r border-gray-200 pr-6">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider mr-2">Публічна</span>
          <UButton to="/blog-posts" variant="ghost" color="gray">Блог</UButton>
        </div>

        <div class="flex items-center gap-1">
          <span class="text-xs font-semibold text-primary-500 uppercase tracking-wider mr-2">Адмін</span>
          <UButton to="/admin/blog-posts" variant="soft" color="primary">Пости</UButton>
          <UButton to="/admin/categories" variant="soft" color="primary">Категорії</UButton>
        </div>

      </nav>
    </div>
  </header>
</template>
