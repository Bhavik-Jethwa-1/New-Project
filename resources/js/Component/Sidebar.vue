<template>
    <div class="w-64 h-screen bg-white border-r flex flex-col justify-between">
        <div>
            <!-- Logo Section -->
            <div class="py-4 pr-8 flex items-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/8/84/Government_of_India_logo.svg"
                    alt="Gov Logo" class="w-80 h-16" />
            </div>

            <!-- Navigation Links -->
            <nav class="mt-4">
                <router-link to="VolunteerDetail" class="block px-4 py-2 hover:bg-gray-100"
                    active-class="bg-blue-100 font-medium">
                    Volunteer Request
                </router-link>
                <router-link to="PersonDetail" class="block px-4 py-2 hover:bg-gray-100"
                    active-class="bg-blue-100 font-medium">
                    Person Details
                </router-link>
            </nav>
        </div>

        <!-- Admin Dropdown -->
        <div class="p-4">
            <div class="relative">
                <select @change="handleSelectChange" class="w-full cursor-pointer border rounded px-3 py-2 text-sm bg-gray-50">
                    <option value="">Admin</option>
                    <option value="logout">Logout</option>
                </select>

            </div>
        </div>
    </div>
</template>

<script>
import { useAuthStore } from '../store/auth';
export default {
    name: 'Sidebar',
    data() {
        return {
            auth: useAuthStore()
        }
    },
    methods: {
        handleSelectChange(e) {
            if (e.target.value === 'logout') {
                this.logout()
            }
        },
        logout() {
            this.auth.logout()
            this.$router.push('/login')
        }
    }

}
</script>
