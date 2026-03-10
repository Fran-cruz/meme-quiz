<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Top bar -->
        <nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center">
                    <div class="flex shrink-0 items-center">
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                        />
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Quiz Finished
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Final Leaderboard
                    </h3>

                    <v-list class="rounded-md bg-transparent">
                        <v-list-item
                            v-for="(player, index) in players"
                            :key="player.id"
                            class="mb-2 rounded-md border border-gray-200 text-gray-900 dark:border-gray-700 dark:text-gray-100"
                        >
                            <div class="flex w-full items-center justify-between">
                                <div>
                                    <strong>#{{ index + 1 }}</strong>
                                    - {{ player.nickname }}
                                </div>
                                <div class="font-medium">
                                    {{ player.correct_count }} / {{ player.total_answered }}
                                </div>
                            </div>
                        </v-list-item>
                    </v-list>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()

const players = ref(page.props.players || [])
</script>
