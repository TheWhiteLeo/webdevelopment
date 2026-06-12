<template>
  <div class="w-full flex items-center justify-between mb-6">
    <div class="text-left">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 tracking-tight">
        Start Your 3 Day Free Trial
      </h1>
    </div>

    <div class="flex items-center">
      <div class="relative flex flex-col items-end mr-4">
        <svg class="w-5 h-5 text-green-500 absolute -top-4 -right-1 rotate-25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 20V18.6C4 15.2397 4 13.5595 4.65396 12.2761C5.2292 11.1471 6.14708 10.2292 7.27606 9.65396C8.55953 9 10.2397 9 13.6 9H20M20 9L15 14M20 9L15 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span class="text-green-500 font-medium text-sm tracking-wide">Save up to 20%</span>
      </div>

      <UTabs
        v-model="tabIndex"
        :items="billingOptions"
        :content="false"
        class="w-45"
        :ui="{
          list: 'bg-gray-100 border border-gray-200 p-0.5 rounded-md',
          indicator: 'hidden',
          trigger: 'transition-all duration-300 data-[state=active]:bg-white data-[state=active]:shadow-sm data-[state=active]:text-gray-900 data-[state=inactive]:text-gray-500 hover:data-[state=inactive]:text-gray-700'
        }"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import type { TabsItem } from '@nuxt/ui'

const props = defineProps<{
  isAnnual: boolean
}>()

const emit = defineEmits(['update:isAnnual'])

const billingOptions = ref<TabsItem[]>([
  { label: 'Annual' },
  { label: 'Monthly' }
])

const tabIndex = computed({
  get: () => props.isAnnual ? '0' : '1',
  set: (val) => {
    emit('update:isAnnual', val == '0')
  }
})
</script>
