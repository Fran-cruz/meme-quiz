<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Session Details
            </h2>
        </template>

        <div class="p-6 max-w-4xl mx-auto text-gray-900 dark:text-gray-100">
            <div v-if="session">
                <h3>Game Code: {{ session.code }}</h3>
                <h3>State: {{ session.status }}</h3>
                <p>Players joined: {{ players.length }}</p>

                <v-list>
                    <v-list-item
                        v-for="player in players"
                        :key="player.id"
                        class="text-gray-900 dark:text-gray-100"
                    >
                        {{ player.nickname }} - Score: {{ player.correct_count || 0 }} / {{ player.total_answered || 0 }}
                    </v-list-item>
                </v-list>

                <div class="mt-4">
                    <v-btn
                        class="p-6 text-gray-900 dark:text-gray-100"
                        @click="startGame"
                        :disabled="players.length === 0 || session.status === 'finished' || session.status === 'playing'"
                    >
                        Start Game
                    </v-btn>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <v-btn
                        color="error"
                        class="ml-2"
                        @click="erasePlayers"
                        :disabled="players.length === 0 || session.status === 'finished'"
                    >
                        Erase Players
                    </v-btn>

                    <v-btn
                        color="warning"
                        class="ml-2"
                        @click="closeRoom"
                        :disabled="session.status !== 'waiting'"
                    >
                        Close Room
                    </v-btn>

                    <v-btn
                        color="secondary"
                        class="ml-2"
                        @click="terminateSession"
                        :disabled="session.status === 'finished'"
                    >
                        Terminate
                    </v-btn>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const session = ref(page.props.session || null)
const players = ref(page.props.players || [])

let pollInterval = null
let polling = false

const pollSession = async () => {
    if (!session.value?.id) return
    if (polling) return

    polling = true

    try {
        const res = await axios.get(`/sessions/${session.value.id}/json`)

        const newSession = res.data.session
        const newPlayers = res.data.players || []

        session.value = {
            ...session.value,
            code: newSession.code,
            status: newSession.status,
            started_at: newSession.started_at,
            ended_at: newSession.ended_at
        }

        players.value = [...newPlayers]
    } catch (err) {
        console.error('Polling failed:', err)
    } finally {
        polling = false
    }
}

onMounted(() => {
    pollInterval = setInterval(pollSession, 2000)
})

onUnmounted(() => {
    if (pollInterval) clearInterval(pollInterval)
})

const erasePlayers = async () => {
    await axios.post(`/sessions/${session.value.id}/erase-players`)
}

const closeRoom = async () => {
    await axios.post(`/sessions/${session.value.id}/close`)
}

const terminateSession = async () => {
    await axios.post(`/sessions/${session.value.id}/terminate`)
}

const startGame = async () => {
    if (players.value.length === 0) return
    await axios.post(`/sessions/${session.value.id}/start`)
}
</script>
