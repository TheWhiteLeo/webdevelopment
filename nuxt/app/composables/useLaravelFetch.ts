import type { UseFetchOptions } from 'nuxt/app'

export function useLaravelFetch<T>(url: string | () => string, options: UseFetchOptions<T> = {}) {
  const config = useRuntimeConfig()

  return useFetch<T>(url, {
    ...options,
    // Вказуємо базову адресу Laravel сервера з налаштувань
    baseURL: config.public.laravelUrl,

    // Тут ми можемо перехоплювати запити перед відправкою
    onRequest({ options }) {
      // Laravel очікує заголовок Accept для правильної роботи з JSON
      options.headers = options.headers || {}
      // @ts-expect-error - ігноруємо помилку типізації для заголовків
      options.headers.Accept = 'application/json'
    }
  })
}
