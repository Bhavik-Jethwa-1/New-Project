<template>
  <div class="flex h-screen">
    <!-- Sidebar (left) -->
    <Sidebar />

    <!-- Main Content (right side) -->
    <div class="flex-1 flex flex-col">
      <!-- Header (top) -->
      <Header />

      <!-- Page Content -->
      <main class="flex-1 p-6 bg-gray-50">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
import Header from '../Component/Header.vue'
import Sidebar from '../Component/Sidebar.vue'

export default {
  name: 'DefaultLayout',
  components: {
    Sidebar,
    Header
  }
}
</script>
