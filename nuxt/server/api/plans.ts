import type { PricingPlan } from '~/types/pricing'
import plansData from '../data/plans.json'

export default defineEventHandler(async (): Promise<PricingPlan[]> => {

  return plansData as PricingPlan[]
})
