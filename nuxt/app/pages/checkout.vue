<template>
  <div class="min-h-screen bg-white">
    <Head>
      <Title>Checkout - Start Your 3-Day Free Trial</Title>
    </Head>

    <header class="w-full bg-[#3f3f3f] py-4 flex justify-center shadow-md">
      <h1 class="text-white font-semibold text-lg">
        Checkout
      </h1>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-8">
      <div class="mb-8">
        <button @click="$router.back()" class="text-gray-500 hover:text-gray-800 flex items-center text-sm mb-6 transition-colors cursor-pointer">
          &lt;&lt; back
        </button>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
          You're Almost In - Start Your 3-Day Free Trial Now!
        </h2>
        <p class="text-gray-600">
          Set up your account to gain instant access! You won't be charged if you decide to cancel within 3 days
        </p>
      </div>

      <div v-if="selectedPlan" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-4">
          <PricingCardBody
            :plan="selectedPlan"
            :isAnnual="isAnnual"
            :show-button="false"
          />
        </div>

        <div class="lg:col-span-8">
          <CheckoutForm
            :plan="selectedPlan"
            :isAnnual="isAnnual"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useSubscriptionStore } from '~/stores/useSubscriptionStore'

const subscriptionStore = useSubscriptionStore()
const { selectedPlan, isAnnual } = storeToRefs(subscriptionStore)

if (!selectedPlan.value) {
  const router = useRouter()
  router.push('/pricing-page')
}
</script>
