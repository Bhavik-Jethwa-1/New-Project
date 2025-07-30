// stores/auth.js
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
    // role : JSON.parse(localStorage.getItem('role')) || null
  }),
  actions: {
    setAuth(data) {
      this.token = data.token
      this.user = data.user
      // this.role = data.user.role
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
      // localStorage.setItem('role', JSON.stringify(data.user.role))
    },
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      // localStorage.removeItem('role')
    }
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
    getToken: (state) => state.token,
    // getRole : (state) => state.user.role
  }
})
