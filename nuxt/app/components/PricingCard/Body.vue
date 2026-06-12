<template>
  <div class="relative bg-white w-full rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col">
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-linear-to-r from-[#70e000] to-[#00bbf9]"></div>

    <div class="p-8 grow flex flex-col">
      <h2 class="text-xl font-bold text-gray-900 mb-4">
        {{ plan.name }} <span v-if="isAnnual">- Annual</span><span v-else>- Monthly</span>
      </h2>

      <div class="mb-2">
        <span class="bg-gray-100 text-gray-500 text-[11px] font-medium px-2 py-1 rounded">
          3-days free then:
        </span>
      </div>

      <div class="flex items-baseline mb-2">
        <span class="text-4xl font-extrabold text-gray-900 tracking-tight">
          ${{ isAnnual ? plan.annualPriceMonth : plan.monthlyPrice }}
        </span>
        <span class="text-gray-500 text-sm ml-1 font-medium">/month</span>
      </div>

      <div v-if="isAnnual" class="h-5 mb-3">
        <p class="text-gray-500 text-[13px]">
          billed yearly at
          <span class="line-through mr-1">${{ plan.originalYearlyTotal }}</span>
          <span class="font-bold text-gray-900">${{ plan.annualPriceTotal }}</span>
        </p>
      </div>

      <div v-if="isAnnual && plan.savings" class="h-8 mb-6">
        <span class="bg-[#e2e8f0] text-green-600 text-[13px] font-bold px-3 py-1.5 rounded-md inline-block">
          {{ plan.savings }} in savings
        </span>
      </div>

      <div @click="handleSelectPlan" v-if="showButton" class="mb-8">
        <button class="w-full bg-linear-to-r from-[#ffd813] to-[#ff8b0b] hover:brightness-110 text-gray-900 py-3 font-bold text-[14px] rounded-md transition-all duration-200 cursor-pointer">
          Try It Free
        </button>
      </div>

      <ul class="space-y-3.5 grow">
        <li v-for="(feature, fIndex) in plan.features" :key="fIndex" class="flex items-start">
          <svg class="w-4 h-4 text-[#70e000] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
          </svg>
          <div class="ml-3">
            <p class="text-[13px] font-semibold text-gray-700 leading-snug" v-html="feature.text"></p>
            <p v-if="feature.subtext" class="text-gray-400 text-[12px] mt-0.5">{{ feature.subtext }}</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PricingPlan } from '~/types/pricing'
import { useSubscriptionStore } from '~/stores/useSubscriptionStore'

const props = withDefaults(defineProps<{
  plan: PricingPlan
  isAnnual: boolean
  showButton?: boolean
}>(), {
  showButton: true
})

const subscriptionStore = useSubscriptionStore()
const router = useRouter()

const handleSelectPlan = () => {
  subscriptionStore.setSubscription(props.plan, props.isAnnual)
  router.push('/checkout')
}
</script>
