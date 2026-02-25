<template>
    <div v-if="quizCompleted" class="text-center p-10 ga-4 text-white">
        <h1 class="text-3xl font-bold">The game has been completed.</h1>
        <p>You can wait for results.</p>
    </div>

    <QuestionTemplate
        v-else
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

/*
  TEMP quiz data
*/
const questions = ref([
    { question: 'What is 2 + 2?', image: null, answers: ['1','2','3','4'], correct: 3 },
    { question: 'Capital of France?', image: null, answers: ['Berlin','Paris','Rome','Madrid'], correct: 2 }
])

const currentIndex = ref(0)
const timeLeft = ref(15)
const quizCompleted = ref(false)

const currentQuestion = computed(() => questions.value[currentIndex.value])

function selectAnswer(index) {
    if (currentIndex.value < questions.value.length - 1) {
        currentIndex.value++
    } else {
        quizCompleted.value = true
    }
}

/* ---------------- SESSION POLLING ---------------- */
const session = ref(null)
const redirected = ref(false)
let interval = null

const pollSession = async () => {
    try {
        const res = await axios.get(`/sessions/${sessionId}`)
        session.value = res.data
    } catch (e) {
        console.error('Failed to poll session:', e)
    }
}

onMounted(() => {
    pollSession()
    interval = setInterval(pollSession, 2000)
})

onUnmounted(() => {
    clearInterval(interval)
})

/* Redirect if host terminates or session finishes */
watch(
    () => session.value?.status,
    (status) => {
        if (!status || redirected.value) return

        if (status === 'finished') {
            redirected.value = true
            window.location.href = `/player/${playerId}/wait`
        }

        if (quizCompleted.value) {
            redirected.value = true
            window.location.href = `/player/${playerId}/wait`
        }
    }
)
</script>
