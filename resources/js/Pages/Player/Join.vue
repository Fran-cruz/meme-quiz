<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';
// --- ESTO ES LO QUE FALTABA (FUNCIONALIDAD) ---
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
// ----------------------------------------------

// IMPORTACIONES VISUALES
import logoMeme from './logoMeme.png';
import magnusLogo from './Magnus_Sistemas_ICC.png';

const playerNickname = ref('');
const gameCode = ref('');
const loading = ref(false);

async function joinGame() {
    // Si no hay datos, ni intentamos la petición
    if (!playerNickname.value || !gameCode.value) {
        alert('Por favor, ingresa tu Nickname y el Código del juego.');
        return;
    }

    loading.value = true;

    try {
        const res = await axios.post('/join', {
            nickname: playerNickname.value,
            code: gameCode.value
        });

        // Redirigir usando la ruta de Ziggy y el router de Inertia
        router.visit(route('player.wait', res.data.id));
    } catch (err) {
        if (err.response?.status === 422) {
            alert(err.response.data.message);
        } else {
            alert('Fallo al entrar. Revisa el código del juego.');
        }
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <GuestLayout>
        <template #logo>
            <div class="hidden"></div>
        </template>

        <div class="matrix-container">
            <div class="matrix-column" style="left: 5%; animation-duration: 4s;">カたレ</div>
            <div class="matrix-column" style="left: 15%; animation-duration: 6s;">AB12</div>
            <div class="matrix-column" style="left: 85%; animation-duration: 5s;">MEME</div>
            <div class="matrix-column" style="left: 95%; animation-duration: 3s;">QUIZ</div>
        </div>

        <div class="relative w-full content-wrapper">
            <div class="flex flex-row justify-center items-center gap-10 mb-8 mt-12">
                <v-img :src="logoMeme" alt="Meme Quiz Logo" width="120" contain class="logo-glow" />
                <v-img :src="magnusLogo" alt="Magnus Logo" width="100" contain class="logo-glow" />
            </div>

            <div class="p-4 w-full max-w-sm mx-auto">
                <v-text-field
                    v-model="playerNickname"
                    label="NICKNAME"
                    variant="outlined"
                    color="primary"
                    base-color="secondary"
                    class="mb-4"
                ></v-text-field>

                <v-text-field
                    v-model="gameCode"
                    label="GAME CODE"
                    variant="outlined"
                    color="primary"
                    base-color="secondary"
                    class="mb-8"
                ></v-text-field>

                <v-btn
                    :loading="loading"
                    @click="joinGame"
                    block
                    height="64"
                    color="primary"
                    class="text-black font-weight-black text-h6 rounded-xl btn-glow"
                >
                    ¡INICIAR!
                </v-btn>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* (Se mantiene el CSS neón que ya teníamos) */
.matrix-container { position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 1; }
.content-wrapper { z-index: 10; }
:deep(.v-field__input) { color: #00FF41 !important; }
.logo-glow { filter: drop-shadow(0 0 10px rgba(0, 255, 65, 0.4)); }
.btn-glow { box-shadow: 0 0 20px rgba(0, 255, 65, 0.6) !important; }
.matrix-column {
    position: absolute;
    top: -100%;
    color: #00FF41;
    font-family: monospace;
    font-size: 1.4rem;
    writing-mode: vertical-rl;
    animation: matrix-fall 5s infinite linear;
    opacity: 0.3;
}
@keyframes matrix-fall {
    0% { top: -100%; opacity: 0; }
    20% { opacity: 0.3; }
    80% { opacity: 0.3; }
    100% { top: 100%; opacity: 0; }
}
</style>
