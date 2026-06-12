export interface PricingFeature {
  text: string
  subtext: string
}

export interface PricingPlan {
  id: number
  name: string
  monthlyPrice: string
  annualPriceMonth: string
  originalYearlyTotal: string
  annualPriceTotal: string
  savings: string
  features: PricingFeature[]
}
