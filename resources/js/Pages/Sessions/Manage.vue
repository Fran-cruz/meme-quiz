<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Game Sessions
            </h2>
        </template>

        <div class="d-flex justify-center ma-4 ga-4">
            <v-btn class="p-6 text-gray-900 dark:text-gray-100" @click="startSession">
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
import { ref } from 'vue'


const loading = ref(false)
const session = ref(null)

function startSession() {
    loading.value = true
    const quizId = 1 // later dynamic

    axios.post(`/sessions/create/${quizId}`)
        .then(res => {
            const sessionId = res.data.id

            console.log('Session created:', sessionId)

            router.visit(`/sessions/manage/${sessionId}`)
            //http://127.0.0.1:8000/sessions/manage/14
        })
        .catch(err => {
            console.error('Error creating session:', err)
            alert('Failed to start session.')
        })
        .finally(() => loading.value = false)
}

const goAllSessions = () => {
    router.visit('/sessions/all')
}
</script>
