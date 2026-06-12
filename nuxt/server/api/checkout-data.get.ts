import type { PricingPlan } from '~/types/pricing'
import plansData from '../data/plans.json'

const allPlans = plansData as PricingPlan[]

export default defineEventHandler(async (event) => {
  const query = getQuery(event)

  const requestedPlanId = Number(query.planId)
  const isAnnual = query.annual === 'true'

  const selectedPlan = allPlans.find(p => p.id === requestedPlanId) || allPlans[1]

  return {
    isAnnual,
    plan: selectedPlan
  }
})
