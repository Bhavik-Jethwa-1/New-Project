<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Volunteer Details</h1>

        <div v-if="loading">Loading...</div>
        <div v-else-if="error" class="text-red-500">{{ error }}</div>
        <div v-else class="space-y-2">
            <div v-for="person in volunteer" :key="person.id" class="p-4 border rounded mb-4">
            <p><strong>Name:</strong> {{ volunteer.name }}</p>
            <p><strong>Email:</strong> {{ volunteer.email }}</p>
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import { useAuthStore } from '../../store/auth';

export default {
    name: 'Volunteer',
    data() {
        return {
            volunteer: {},
            loading: true,
            error: null,
        };
    },
    mounted() {
        const auth = useAuthStore()
        axios.get('/api/volunteer/person-details', {
            headers: {
                // Authorization: `Bearer ${localStorage.getItem('token')}`
                Authorization: `Bearer ${auth.token}`
            }
        })
            .then(response => {
                this.volunteer = response.data[0];
            })
            .catch(error => {
                this.error = 'Failed to fetch volunteer data.';
                console.error(error);
            })
            .finally(() => {
                this.loading = false;
            });
    }
};
</script>
