<template>
    <GuestLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Join Game
            </h2>
        </template>

        <div class="p-6 max-w-md mx-auto">
            <v-text-field
                label="Your Nickname"
                v-model="playerNickname"
                class="dark:text-gray-900"
            />

            <v-text-field
                label="Game Code"
                v-model="gameCode"
                class="dark:text-gray-900"
            />

            <v-btn
                :loading="loading"
                @click="joinGame"
                block
                class="p-6 text-gray-900 dark:text-gray-100"
            >
                Join Game
            </v-btn>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { ref } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const playerNickname = ref('')
const gameCode = ref('')
const loading = ref(false)

async function joinGame() {
    loading.value = true

    try {
        const res = await axios.post('/join', {
            nickname: playerNickname.value,
            code: gameCode.value
        })

        router.visit(route('player.wait', res.data.id))
    } catch (err) {
        if (err.response?.status === 422) {
            alert(err.response.data.message)
        } else {
            alert('Failed to join. Check the game code.')
        }
    } finally {
        loading.value = false
    }
}
</script>
