<template>
    <GuestLayout>
        <div class="matrix-container">
            <div class="matrix-column" style="left: 5%; animation-duration: 4s;">WAITING</div>
            <div class="matrix-column" style="left: 15%; animation-duration: 6s;">PLAYER_ID</div>
            <div class="matrix-column" style="left: 85%; animation-duration: 5s;">LOADING</div>
            <div class="matrix-column" style="left: 95%; animation-duration: 3s;">SYNC</div>
        </div>

        <div class="relative z-10 p-6 max-w-2xl mx-auto text-center content-wrapper">

            <div class="bg-black/80 border-2 border-green-500/40 rounded-3xl p-8 shadow-[0_0_30px_rgba(0,255,65,0.2)] backdrop-blur-md">
                <h2 class="text-green-500 font-mono text-xl tracking-widest mb-6 animate-pulse">
                    ¡ESTAMOS POR COMENZAR!
                </h2>

                <div class="space-y-4 font-mono">
                    <p class="text-2xl text-white">
                         Apodo: <span class="text-green-400 glow-text">{{ player.nickname }}</span>

                    </p>

                    <div class="inline-block px-4 py-2 bg-green-500/10 border border-green-500/20 rounded-lg">
                        <p class="text-green-500">
                            GAME CODE: <span class="font-black tracking-tighter">{{ session.code }}</span>
                        </p>
                    </div>

                    <div class="mt-10 py-6">
                        <div v-if="session.status === 'waiting'" class="status-box waiting">
                            <p class="text-lg">ESPERANDO INICIO DEL JUEGO...</p>
                            <div class="loading-bar mt-4"></div>
                        </div>

                        <div v-else-if="session.status === 'playing'" class="status-box playing">
                            <p class="text-lg text-blue-400">EJECUCIÓN EN CURSO...</p>
                        </div>

                        <div v-else-if="session.status === 'closed'" class="status-box closed">
                            <p class="text-lg text-yellow-500">SESIÓN FINALIZADA</p>
                        </div>

                        <div v-else class="status-box terminated">
                            <p class="text-lg text-red-500">CONEXIÓN ABORTADA</p>
                        </div>
                        <div class="flex flex-row justify-center items-center gap-8 mb-10 mt-6">
                            <div class="h-10 w-px bg-green-500 opacity-30"></div>
                            <v-img :src="magnusLogo" width="100" contain class="logo-glow" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { reactive, onMounted, onUnmounted, watch } from 'vue'
import axios from 'axios'

// Importación de logos
import logoMeme from './logoMeme.png'
import magnusLogo from './Magnus_Sistemas_ICC.png'

const pageProps = usePage().props
const player = pageProps.player
const session = reactive({ ...pageProps.session })
const quizCompleted = pageProps.quizCompleted ?? false

let intervalId = null

const fetchSession = async () => {
    try {
        const res = await axios.get(`/sessions/${session.id}`)
        Object.assign(session, res.data)
    } catch (err) {
        console.error('Failed to fetch session status:', err)
    }
}

onMounted(async () => {
    await fetchSession()
    intervalId = setInterval(fetchSession, 3000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})

watch(
    () => session.status,
    (newStatus) => {
        if (newStatus === 'playing' && !quizCompleted) {
            window.location.href = `/player/${player.id}/questions`
        } else if (newStatus === 'finished') {
            window.location.href = `/sessions/${session.id}/finished`
        }
    },
    { immediate: true }
)
</script>

<style scoped>
.matrix-container {
    position: fixed;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

.content-wrapper { z-index: 10; }

.logo-glow { filter: drop-shadow(0 0 10px rgba(0, 255, 65, 0.4)); }

.glow-text { text-shadow: 0 0 10px rgba(0, 255, 65, 0.8); }

/* BARRA DE CARGA ANIMADA */
.loading-bar {
    height: 4px;
    width: 100%;
    background: rgba(0, 255, 65, 0.1);
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.loading-bar::after {
    content: '';
    position: absolute;
    left: -50%;
    height: 100%;
    width: 50%;
    background: #00FF41;
    box-shadow: 0 0 10px #00FF41;
    animation: loading-slide 2s infinite linear;
}

@keyframes loading-slide {
    from { left: -50%; }
    to { left: 150%; }
}

.matrix-column {
    position: absolute;
    top: -100%;
    color: #00FF41;
    font-family: monospace;
    font-size: 1.4rem;
    writing-mode: vertical-rl;
    animation: matrix-fall infinite linear;
    opacity: 0.2;
}

@keyframes matrix-fall {
    0% { top: -100%; opacity: 0; }
    10% { opacity: 0.3; }
    90% { opacity: 0.3; }
    100% { top: 100%; opacity: 0; }
}
</style>
