<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Game Sessions
            </h2>
        </template>

        <div class="d-flex justify-center ma-4 ga-4">
            <v-btn class="p-6 text-gray-900 dark:text-gray-100" @click="createSession">
                Start New Session
            </v-btn>
            <v-btn class="p-6 text-gray-900 dark:text-gray-100" @click="goAllSessions">
                View All Sessions
            </v-btn>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'

const createSession = async () => {
    try {
        const res = await axios.post('/sessions/create') // make a POST route in GameSessionController
        router.visit(`/sessions/manage/${res.data.id}`)
    } catch (err) {
        console.error(err)
        alert('Failed to create session.')
    }
}

const goAllSessions = () => {
    router.visit('/sessions/all')
}
</script>
