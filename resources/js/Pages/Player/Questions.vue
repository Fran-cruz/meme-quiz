<template>
    <div v-if="quizCompleted" class="text-center p-10 ga-4 text-white">
        <h1 class="text-3xl font-bold">The game has been completed.</h1>
        <p>You can wait for results.</p>
    </div>

    <div v-else-if="!currentQuestion" class="text-center p-10 text-white">
        <h1 class="text-2xl font-bold">Loading question...</h1>
    </div>

    <div v-if="showMeme" class="meme-overlay">
        <img :src="currentMeme" class="meme-image" />
    </div>

    <QuestionTemplate
        v-if="!quizCompleted && currentQuestion"
        :question="currentQuestion.question"
        :image="currentQuestion.image"
        :answers="currentQuestion.answers"
        :timeLeft="timeLeft"
        @answerSelected="selectAnswer"
    />
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import QuestionTemplate from '@/Pages/Components/QuestionTemplate.vue'
import { usePage } from '@inertiajs/vue3'

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
                selectAnswer({ id: 81 })
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

        // If all questions are already answered, mark completed and go back to wait
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

<style>
.meme-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.meme-image {
    max-width: 80%;
    max-height: 80%;
}
</style>
