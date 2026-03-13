<template>
    <div
        class="flex min-h-screen flex-col items-center bg-black pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-primary" />
            </Link>
        </div>

        <header class="w-full max-w-md px-6 text-center">
            <slot name="header" />
        </header>

        <div
            class="mt-6 w-full overflow-hidden bg-transparent px-6 py-4 sm:max-w-md"
        >
            <slot />
        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/vue3'
</script>
