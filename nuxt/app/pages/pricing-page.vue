<template>
  <Head>
    <Title>Pricing List</Title>
  </Head>

  <div class="w-full max-w-6xl mx-auto mb-16 pt-8 flex flex-col items-center">

    <PricingCardHeader v-model:isAnnual="isAnnual" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-6xl mx-auto">
      <template v-if="plans && plans.length > 0">
        <PricingCardBody
          v-for="plan in plans"
          :key="plan.id"
          :plan="plan"
          :isAnnual="isAnnual"
        />
      </template>

      <div v-else class="col-span-1 md:col-span-3 text-center text-gray-500 py-8">
        No pricing plans available at the moment.
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const { data: plans } = await useFetch('/api/plans')

const isAnnual = ref(true)
</script>
