import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', () => {
  const name = ref('Гість')
  const isLoggedIn = ref(false)
  const loginTime = ref<string | null>(null)
  const profileData = ref(null)
  const isLoading = ref(false)

  const welcomeMessage = computed(() => `Вітаємо, ${name.value}!`)

  function login(userName: string) {
    name.value = userName
    isLoggedIn.value = true
    loginTime.value = new Date().toLocaleTimeString()
  }

  function logout() {
    name.value = 'Гість'
    isLoggedIn.value = false
    loginTime.value = null
  }

  async function fetchProfile() {
    isLoading.value = true
    try {
      const response = await $fetch('https://api.github.com/users/octocat')
      profileData.value = response
    } finally {
      isLoading.value = false
    }
  }

  return {
    name,
    isLoggedIn,
    loginTime,
    welcomeMessage,
    profileData,
    isLoading,
    login,
    logout,
    fetchProfile
  }
})
