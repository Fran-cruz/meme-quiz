<template>
    <div class="matrix-container">
        <div class="matrix-column" style="left: 10%; animation-duration: 5s;">QUIZ</div>
        <div class="matrix-column" style="left: 85%; animation-duration: 4s;">LIVE</div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col overflow-hidden">

        <div class="w-full flex justify-center py-6 shrink-0">
            <v-img :src="logoMeme" max-width="180" height="100" contain class="logo-glow" />
        </div>

        <div class="flex-grow flex flex-col items-center justify-center px-4">

            <div v-if="quizCompleted" class="text-center p-10 bg-black/80 border-2 border-green-500 rounded-3xl shadow-neon-strong">
                <h1 class="text-3xl font-black text-green-500 mb-2 uppercase tracking-tighter">SISTEMA FINALIZADO</h1>
                <p class="text-white font-mono opacity-70">Calculando resultados en la terminal...</p>
            </div>

            <div v-else-if="!currentQuestion" class="text-center p-10">
                <div class="loading-scanner mx-auto mb-4"></div>
                <h1 class="text-xl font-mono text-green-500 animate-pulse">> CARGANDO_DATOS...</h1>
            </div>

            <div v-if="!quizCompleted && currentQuestion" class="w-full max-w-4xl py-4">
                <QuestionTemplate
                    :question="currentQuestion.question"
                    :image="currentQuestion.image"
                    :answers="currentQuestion.answers"
                    :timeLeft="timeLeft"
                    @answerSelected="selectAnswer"
                    class="neon-question-container"
                />
            </div>
        </div>

        <div v-if="showMeme" class="meme-overlay backdrop-blur-md">
            <div class="relative p-2 bg-green-500 rounded-lg shadow-neon-strong animate-bounce-short">
                <img :src="currentMeme" class="meme-image rounded" />
            </div>
        </div>

        <div class="fixed bottom-4 right-4 opacity-30 pointer-events-none">
            <v-img :src="magnusLogo" width="80" contain />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import QuestionTemplate from '@/Pages/Components/QuestionTemplate.vue'
import { usePage } from '@inertiajs/vue3'

// IMPORTACIÓN DE LOGOS
import logoMeme from './logoMeme.png'
import magnusLogo from './Magnus_Sistemas_ICC.png'

const page = usePage()
const playerId = page.props.player.id
const sessionId = page.props.session.id

/* ---------------- QUIZ STATE ---------------- */
const questions = ref([])
const currentIndex = ref(0)
const quizCompleted = ref(false)
const answeredQuestions = ref({})

/* ---------------- MEMES & SOUND ---------------- */
const showMeme = ref(false)
const currentMeme = ref(null)
const goodSound = new Audio('/sounds/good.mp3')
const badSound = new Audio('/sounds/bad.mp3')

/* ---------------- TIMER ---------------- */
const timeLeft = ref(0)
let timerInterval = null
const answered = ref(false)

const currentQuestion = computed(() => questions.value[currentIndex.value] ?? null)

const startTimer = () => {
    clearInterval(timerInterval)
    if (!currentQuestion.value) return

    answered.value = currentQuestion.value.id in answeredQuestions.value
    timeLeft.value = currentQuestion.value.time_limit

    timerInterval = setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--
        } else {
            clearInterval(timerInterval)
            if (!answered.value) {
                selectAnswer({ id: 81 }) // ID por defecto para tiempo agotado
            }
        }
    }, 1000)
}

/* ---------------- FETCH QUESTIONS ---------------- */
const loadQuestions = async () => {
    try {
        const res = await axios.get(`/player/${playerId}/questions-data`)
        questions.value = res.data.questions || []
        answeredQuestions.value = res.data.player_answers || {}

        if (!questions.value.length) {
            quizCompleted.value = true
            return
        }

        const firstUnansweredIndex = questions.value.findIndex(
            q => !(q.id in answeredQuestions.value)
        )

        if (firstUnansweredIndex === -1) {
            quizCompleted.value = true
            setTimeout(() => {
                window.location.href = `/player/${playerId}/wait`
            }, 1000)
            return
        }

        currentIndex.value = firstUnansweredIndex
        startTimer()
    } catch (e) {
        console.error('Failed loading questions:', e)
    }
}

/* ---------------- ANSWER HANDLER ---------------- */
const selectAnswer = async (answer) => {
    if (!currentQuestion.value || answered.value) return

    answered.value = true
    answeredQuestions.value[currentQuestion.value.id] = answer?.id || 81

    clearInterval(timerInterval)
    const responseTime = currentQuestion.value.time_limit - timeLeft.value

    try {
        const res = await axios.post(`/player/${playerId}/answer`, {
            question_id: currentQuestion.value.id,
            answer_id: answer?.id || 81,
            response_time: responseTime
        })

        const data = res.data

        if (data.is_correct) goodSound.play()
        else badSound.play()

        currentMeme.value = data.meme
        showMeme.value = true

        setTimeout(() => {
            showMeme.value = false
            if (currentIndex.value < questions.value.length - 1) {
                currentIndex.value++
                startTimer()
            } else {
                quizCompleted.value = true
                setTimeout(() => {
                    window.location.href = `/player/${playerId}/wait`
                }, 1500)
            }
        }, 2000)
    } catch (e) {
        console.error('Error submitting answer:', e)
    }
}

/* ---------------- SESSION POLLING ---------------- */
const session = ref(null)
const redirected = ref(false)
let sessionInterval = null

const pollSession = async () => {
    try {
        const res = await axios.get(`/sessions/${sessionId}`)
        session.value = res.data
    } catch (e) {
        console.error('Failed to poll session:', e)
    }
}

onMounted(async () => {
    await loadQuestions()
    await pollSession()
    sessionInterval = setInterval(pollSession, 2000)
})

onUnmounted(() => {
    clearInterval(timerInterval)
    clearInterval(sessionInterval)
})

/* ---------------- REDIRECT IF SESSION FINISHED ---------------- */
watch(
    () => session.value?.status,
    (status) => {
        if (!status || redirected.value) return
        if (status === 'finished') {
            redirected.value = true
            window.location.href = `/player/${playerId}/wait`
        }
    }
)
</script>

<style scoped>
.matrix-container {
    position: fixed;
    inset: 0;
    background: black;
    z-index: 1;
}

/* Inyectar estilos neón a los botones de QuestionTemplate */
:deep(.neon-question-container) button,
:deep(.neon-question-container) .v-btn {
    border: 2px solid rgba(0, 255, 65, 0.4) !important;
    background: rgba(0, 30, 0, 0.7) !important;
    color: #00FF41 !important;
    font-family: 'Courier New', monospace !important;
    box-shadow: 0 0 10px rgba(0, 255, 65, 0.2);
    margin-bottom: 12px;
}

.logo-glow {
    filter: drop-shadow(0 0 15px rgba(0, 255, 65, 0.6));
}

.shadow-neon-strong {
    box-shadow: 0 0 30px rgba(0, 255, 65, 0.4);
}

.meme-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.meme-image {
    max-width: 80vw;
    max-height: 70vh;
}

.loading-scanner {
    width: 100px;
    height: 3px;
    background: #00FF41;
    box-shadow: 0 0 15px #00FF41;
    animation: scan 2s infinite ease-in-out;
}

@keyframes scan {
    0%, 100% { transform: scaleX(0.5); opacity: 0.3; }
    50% { transform: scaleX(1.2); opacity: 1; }
}

.matrix-column {
    position: absolute;
    top: -100%;
    color: #003300;
    font-family: monospace;
    font-size: 1.2rem;
    writing-mode: vertical-rl;
    animation: matrix-fall infinite linear;
}

@keyframes matrix-fall {
    0% { top: -100%; }
    100% { top: 100%; }
}
</style>
