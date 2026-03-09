<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                Quiz Finished
            </h2>
        </template>

        <div class="p-6 max-w-4xl mx-auto text-gray-900 dark:text-gray-100">

            <h3 class="text-lg mb-4">Final Leaderboard</h3>

            <v-list>
                <v-list-item
                    v-for="(player, index) in players"
                    :key="player.id"
                >
                    <strong>#{{ index + 1 }}</strong>
                    — {{ player.nickname }}
                    ({{ player.correct_count }} points)
                </v-list-item>
            </v-list>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()

const players = ref(page.props.players || [])
</script>
