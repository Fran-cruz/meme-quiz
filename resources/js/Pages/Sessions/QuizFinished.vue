<template>
    <div class="min-h-screen bg-black text-green-500 font-mono relative overflow-hidden">

        <div class="matrix-container">
            <div class="matrix-column" style="left: 15%; animation-duration: 4s;">FINAL</div>
            <div class="matrix-column" style="left: 85%; animation-duration: 6s;">STATS</div>
        </div>

        <div class="relative z-10">
            <header class="border-b border-green-900 bg-black/80 backdrop-blur-md">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="text-2xl font-black tracking-tighter glow-text">
                        > QUIZ_FINISHED_LOG
                    </h2>
                    <span class="text-xs animate-pulse text-green-800">STATUS: ARCHIVED</span>
                </div>
            </header>

            <main class="py-10">
                <div class="mx-auto max-w-2xl px-4">
                    <div class="rounded-3xl border-2 border-green-500/30 bg-black/60 p-8 shadow-[0_0_50px_rgba(0,255,65,0.1)] backdrop-blur-md">

                        <h3 class="mb-8 text-center text-xl font-bold tracking-[0.2em] uppercase text-green-400">
                            --- RESULTADOS FINALES ---
                        </h3>

                        <div class="space-y-4">
                            <div
                                v-for="(player, index) in players"
                                :key="player.id"
                                class="relative group"
                            >
                                <div
                                    class="flex items-center justify-between p-4 rounded-xl border border-green-500/20 bg-green-500/5 transition-all hover:border-green-400 hover:bg-green-500/10 hover:shadow-[0_0_15px_rgba(0,255,65,0.2)]"
                                    :class="{'border-green-400 bg-green-500/10 shadow-[0_0_10px_rgba(0,255,65,0.1)]': index === 0}"
                                >
                                    <div class="flex items-center gap-4">
                                        <span class="text-2xl font-black italic opacity-50" :class="{'text-yellow-500 opacity-100': index === 0}">
                                            #{{ index + 1 }}
                                        </span>
                                        <div class="flex flex-col">
                                            <span class="text-lg font-bold text-white tracking-wide uppercase">{{ player.nickname }}</span>
                                            <span class="text-[10px] text-green-700 tracking-widest">USER_ID: {{ player.id }}</span>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xl font-black text-green-500">
                                            {{ player.correct_count }} <span class="text-xs opacity-50">/ {{ player.total_answered }}</span>
                                        </div>
                                        <div class="text-[9px] uppercase tracking-tighter text-green-800">Accuracy_Score</div>
                                    </div>
                                </div>

                                <div v-if="index === 0" class="absolute -top-1 -right-1 w-3 h-3 border-t-2 border-r-2 border-yellow-500"></div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-green-900/50 text-center">
                            <p class="text-[10px] text-green-900 tracking-[0.5em] animate-pulse">
                                END_OF_TRANSMISSION
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const players = ref(page.props.players || [])
</script>

<style scoped>
.glow-text {
    text-shadow: 0 0 10px rgba(0, 255, 65, 0.7);
}

.matrix-container {
    position: fixed;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

.matrix-column {
    position: absolute;
    top: -100%;
    color: #00FF41;
    font-family: monospace;
    font-size: 1.2rem;
    writing-mode: vertical-rl;
    animation: matrix-fall infinite linear;
    opacity: 0.1;
}

@keyframes matrix-fall {
    0% { top: -100%; opacity: 0; }
    20% { opacity: 0.1; }
    80% { opacity: 0.1; }
    100% { top: 100%; opacity: 0; }
}
</style>
