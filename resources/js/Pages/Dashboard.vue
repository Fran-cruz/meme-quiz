<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';


import { ref } from 'vue'
import axios from 'axios'

const loading = ref(false)
const session = ref(null)
const players = ref([])

function startSession() {
    loading.value = true
    const quizId = 1 // Replace with dynamic quiz selection

    axios.post(`/sessions/create/${quizId}`)
        .then(res => {
            session.value = res.data
            console.log('Session created:', session.value)
            pollPlayers() // start polling for joined players
        })
        .catch(err => {
            console.error('Error creating session:', err)
            alert('Failed to start session.')
        })
        .finally(() => loading.value = false)
}

// Poll players every 2 seconds
function pollPlayers() {
    if (!session.value) return

    setInterval(async () => {
        try {
            const res = await axios.get(`/sessions/${session.value.id}/players`)
            players.value = res.data
        } catch (err) {
            console.error('Failed to fetch players', err)
        }
    }, 2000)
}

// Start the quiz
function startGame() {
    alert('Game started!')
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        You're logged in as Sudo!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
