import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { PricingPlan } from '~/types/pricing'

export interface CheckoutFormData {
  fullName: string
  address: string
}

export const useSubscriptionStore = defineStore('subscription', () => {
  const selectedPlan = ref<PricingPlan | null>(null)
  const isAnnual = ref<boolean>(true)
  const isSubmitting = ref(false)
  const successMessage = ref('')

  function setSubscription(plan: PricingPlan, annual: boolean) {
    selectedPlan.value = plan
    isAnnual.value = annual
  }

  async function submitPayment(formData: CheckoutFormData) {
    if (!selectedPlan.value) {
      console.error('Неможливо здійснити оплату: план не обрано')
      return
    }

    isSubmitting.value = true
    successMessage.value = ''
    try {
      const response = await $fetch('/api/subscription/create', {
        method: 'POST',
        body: {
          ...formData,
          planId: selectedPlan.value.id,
          isAnnual: isAnnual.value
        }
      })
      successMessage.value = response.message
    } catch (error) {
      console.error(error)
      alert('Помилка оплати')
    } finally {
      isSubmitting.value = false
    }
  }

  return { selectedPlan, isAnnual, isSubmitting, successMessage, setSubscription, submitPayment }
})
