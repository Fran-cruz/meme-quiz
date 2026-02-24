<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Waiting Room
            </h2>
        </template>

        <div class="p-6 max-w-md mx-auto text-center">
            <p class="p-6 text-gray-900 dark:text-gray-100">
                Welcome, {{ player?.nickname }}!
            </p>
            <p class="p-6 text-gray-900 dark:text-gray-100">
                Game Code: {{ session?.code }}
            </p>
            <p class="p-6 text-gray-900 dark:text-gray-100" v-if="session?.status === 'waiting' || session?.status === 'playing'">
                Waiting for the game to start...
            </p>
            <p class="p-6 text-gray-900 dark:text-gray-100" v-else-if="session?.status === 'playing'">
                The game has started.
            </p>
            <p class="p-6 text-red-600 dark:text-red-400" v-else>
                This game has been terminated.
            </p>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

// Get initial props safely
const pageProps = usePage().props
const player = pageProps.player
const session = ref(pageProps.session) // <-- no .value

// Polling interval
let intervalId = null

const fetchSession = async () => {
    try {
        const res = await axios.get(`/sessions/${session.value.id}`)
        session.value = res.data
    } catch (err) {
        console.error('Failed to fetch session status:', err)
    }
}

onMounted(() => {
    // Poll every 3 seconds
    intervalId = setInterval(fetchSession, 3000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>
