<template>
  <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-gray-800">Volunteer Management</h1>
    <div class="flex items-center space-x-4">
      <span class="text-sm text-gray-600">{{ auth.user?.full_name || 'Admin' }}</span>
    </div>
  </header>
</template>

<script>
import { useAuthStore } from '../store/auth'

export default {
  name: 'Header',
  data() {
    return {
      auth: useAuthStore()
    }
  }
}
</script>
