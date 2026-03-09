<template>
    <GuestLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Waiting Room
            </h2>
        </template>

        <div class="p-6 max-w-md mx-auto text-center">
            <p class="p-6 text-gray-900 dark:text-gray-100">
                Welcome, {{ player.nickname }}!
            </p>
            <p class="p-6 text-gray-900 dark:text-gray-100">
                Game Code: {{ session.code }}
            </p>

            <p v-if="session.status === 'waiting'" class="p-6 text-gray-900 dark:text-gray-100">
                Waiting for the game to start...
            </p>
            <p v-else-if="session.status === 'playing'" class="p-6 text-blue-600 dark:text-blue-400">
                The game has started.
            </p>
            <p v-else-if="session.status === 'closed'" class="p-6 text-yellow-600 dark:text-yellow-400">
                The game session is closed.
            </p>
            <p v-else class="p-6 text-red-600 dark:text-red-400">
                This game has been terminated.
            </p>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { reactive, onMounted, onUnmounted, watch } from 'vue'
import axios from 'axios'

const pageProps = usePage().props
const player = pageProps.player
const session = reactive({ ...pageProps.session })

let intervalId = null

const fetchSession = async () => {
    try {
        const res = await axios.get(`/sessions/${session.id}`)
        Object.assign(session, res.data)
    } catch (err) {
        console.error('Failed to fetch session status:', err)
    }
}

onMounted(() => {
    intervalId = setInterval(fetchSession, 3000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})

watch(
    () => session.status,
    (newStatus) => {
        if (newStatus === 'playing') {
            window.location.href = `/player/${player.id}/questions`
        }
    }
)
</script>
