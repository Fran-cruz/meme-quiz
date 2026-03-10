<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Game Sessions
            </h2>
        </template>

        <div class="max-w-xl mx-auto p-6">
            <v-select
                v-model="selectedQuizId"
                :items="quizOptions"
                item-title="title"
                item-value="id"
                label="Select Quiz"
                class="mb-4"
                density="comfortable"
                variant="outlined"
            />

            <div class="d-flex justify-center ma-4 ga-4">
                <v-btn
                    class="p-6 text-gray-900 dark:text-gray-100"
                    @click="startSession"
                    :loading="loading"
                    :disabled="!selectedQuizId"
                >
                    Start New Session
                </v-btn>

                <v-btn
                    class="p-6 text-gray-900 dark:text-gray-100"
                    @click="goAllSessions"
                >
                    View All Sessions
                </v-btn>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, computed } from 'vue'

const page = usePage()

const loading = ref(false)
const selectedQuizId = ref(null)

const quizzes = page.props.quizzes || []

const quizOptions = computed(() => quizzes.map(quiz => ({
    id: quiz.id,
    title: quiz.title
})))

function startSession() {
    if (!selectedQuizId.value) {
        alert('Please select a quiz first.')
        return
    }

    loading.value = true

    axios.post(`/sessions/create/${selectedQuizId.value}`)
        .then(res => {
            const sessionId = res.data.id
            router.visit(`/sessions/manage/${sessionId}`)
        })
        .catch(err => {
            console.error('Error creating session:', err)
            alert('Failed to start session.')
        })
        .finally(() => {
            loading.value = false
        })
}

function goAllSessions() {
    router.visit('/sessions/all')
}
</script>
