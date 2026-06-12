<template>
  <div class="bg-white border border-gray-100 rounded-xl shadow-lg p-6 md:p-8">
    <div class="mb-8">
      <h3 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h3>
      <div class="space-y-3 text-sm">
        <div class="flex justify-between text-gray-700">
          <span>{{ isAnnual ? 'Annual Plan' : 'Monthly Plan' }}</span>
          <span>${{ isAnnual ? plan.annualPriceTotal : plan.monthlyPrice }}.00</span>
        </div>
        <div class="flex justify-between text-gray-700">
          <span>Total Due <span class="text-gray-400 text-xs">(*not including sales tax where applicable)</span></span>
          <span>${{ isAnnual ? plan.annualPriceTotal : plan.monthlyPrice }}.00</span>
        </div>
        <div class="flex justify-between font-bold text-gray-900 pt-2 border-t border-gray-100">
          <span>Due Today</span>
          <span>$0.00</span>
        </div>
      </div>
      <div class="mt-4 bg-gray-50 text-gray-600 text-center py-3 rounded-md text-sm font-medium">
        Includes 3-Day Free Trial
      </div>
    </div>

    <form @submit.prevent="submitSubscription">
      <div class="mb-6 flex items-center text-lg font-bold text-gray-800">
        Billing Information
        <span class="ml-2 text-gray-400 cursor-help" title="Your data is secure">&#9432;</span>
      </div>

      <div class="mb-5">
        <label class="block text-sm text-gray-600 mb-1">Card Details</label>
        <div class="flex items-center border border-gray-300 rounded-md p-3 bg-gray-50">
          <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
          </svg>
          <input type="text" placeholder="Number" class="bg-transparent grow outline-hidden text-sm" disabled>
          <input type="text" placeholder="MM / YY" class="bg-transparent w-20 text-center outline-hidden text-sm" disabled>
          <input type="text" placeholder="CVC" class="bg-transparent w-16 text-center outline-hidden text-sm" disabled>
        </div>
      </div>

      <div class="mb-6">
        <label class="block text-sm text-gray-600 mb-1">Address</label>
        <div class="border border-gray-300 rounded-md p-4">
          <div class="mb-4">
            <label class="block text-sm text-gray-500 mb-1">Full name</label>
            <input
              v-model="form.fullName"
              type="text"
              class="w-full border border-gray-200 rounded-md p-2 text-sm focus:border-gray-400 outline-hidden transition-colors"
            >
          </div>
          <div>
            <label class="block text-sm text-gray-500 mb-1">Address</label>
            <input
              v-model="form.address"
              type="text"
              class="w-full border border-gray-200 rounded-md p-2 text-sm focus:border-gray-400 outline-hidden transition-colors"
            >
          </div>
        </div>
      </div>

      <div class="mb-8 flex items-start">
        <input
          id="consent"
          v-model="form.consent"
          type="checkbox"
          class="mt-1 w-4 h-4 text-gray-800 rounded border-gray-300 cursor-pointer"
          required
        >
        <label for="consent" class="ml-3 text-[12px] leading-tight text-gray-600 cursor-pointer">
          I consent to <a href="#" class="underline font-bold text-gray-700">Terms of Use</a> and understand my 3-day free trial will automatically convert to ${{ plan.annualPriceTotal }}.00 per year starting on <span class="font-bold">{{ renewalDate }}</span>. The yearly fee will be automatically charged each year going forward unless I cancel my account at least one (1) business day before the end of the current billing period, which can be done by calling (888) 463-3163.
        </label>
      </div>

      <button
        type="submit"
        :disabled="subscriptionStore.isSubmitting"
        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-md transition-colors duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {{ subscriptionStore.isSubmitting ? 'Processing...' : 'Try It Free' }}
      </button>

      <p v-if="subscriptionStore.successMessage" class="mt-4 text-green-600 font-medium text-sm">
        {{ subscriptionStore.successMessage }}
      </p>
    </form>
  </div>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'
import type { PricingPlan } from '~/types/pricing'

const props = defineProps<{
  plan: PricingPlan
  isAnnual: boolean
}>()

const subscriptionStore = useSubscriptionStore()

const form = reactive({
  fullName: '',
  address: '',
  consent: false
})

const renewalDate = computed(() => {
  const date = new Date()
  date.setDate(date.getDate() + 3)
  date.setFullYear(date.getFullYear() + 1)

  return date.toLocaleDateString('en-US', {
    month: '2-digit',
    day: '2-digit',
    year: 'numeric'
  })
})

const submitSubscription = async () => {
  if (!form.consent) return

  await subscriptionStore.submitPayment({
    fullName: form.fullName,
    address: form.address
  })
}
</script>
