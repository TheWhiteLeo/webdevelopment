// server/api/subscription/create.post.ts
export default defineEventHandler(async (event) => {
  // Читаємо тіло запиту (дані з нашої форми)
  const body = await readBody(event)

  // Тут у майбутньому буде логіка створення підписки через Stripe або інший сервіс
  console.log('Received subscription data:', body)

  // Повертаємо успішну відповідь
  return {
    success: true,
    message: 'Subscription created successfully',
    orderId: 'ORD-' + Math.floor(Math.random() * 1000000)
  }
})
