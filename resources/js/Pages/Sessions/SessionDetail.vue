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
                <p>Players joined: {{ players.length }}</p>

                <v-list>
                    <v-list-item
                        v-for="player in players"
                        :key="player.id"
                        class="text-gray-900 dark:text-gray-100"
                    >
                        {{ player.nickname }}
                    </v-list-item>
                </v-list>

                <v-btn
                    class="p-6 text-gray-900 dark:text-gray-100"
                    @click="startGame"
                    :disabled="players.length === 0 || session.status !== 'waiting'"
                >
                    Start Game
                </v-btn>

                <v-btn color="error" class="ml-2" @click="erasePlayers">
                    Erase Players
                </v-btn>

                <v-btn color="warning" class="ml-2" @click="closeRoom">
                    Close Room
                </v-btn>

                <v-btn color="secondary" class="ml-2" @click="terminateSession">
                    Terminate
                </v-btn>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { ref } from 'vue'

const { session, players } = usePage().props.value

const erasePlayers = async () => {
    await axios.post(`/sessions/${session.id}/erase-players`)
    location.reload()
}

const closeRoom = async () => {
    await axios.post(`/sessions/${session.id}/close`)
    location.reload()
}

const terminateSession = async () => {
    await axios.post(`/sessions/${session.id}/terminate`)
    location.reload()
}

const startGame = async () => {
    if (players.length === 0) return
    await axios.post(`/sessions/${session.id}/start`)
    location.reload()
}
</script>
