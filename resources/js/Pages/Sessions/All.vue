<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight dark:text-gray-200">
                All Game Sessions
            </h2>
        </template>

        <div class="p-6 max-w-4xl mx-auto space-y-4">
            <div
                v-for="session in sessions"
                :key="session.id"
                class="p-4 rounded-lg bg-surface dark:bg-gray-800 shadow-md flex justify-between items-center"
            >
                <div>
                    <p><strong>Session ID:</strong> {{ session.id }}</p>
                    <p><strong>Game Code:</strong> {{ session.code }}</p>
                    <p><strong>Players:</strong> {{ session.players.length }}</p>
                    <p><strong>Status:</strong> {{ session.status }}</p>
                </div>
                <v-btn color="primary" @click="showSession(session.id)">
                    Show More
                </v-btn>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const sessions = usePage()?.props?.value?.sessions || []

const showSession = (id) => {
    router.visit(`/sessions/manage/${id}`)
}
</script>
