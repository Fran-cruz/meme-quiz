<!--<template>-->
<!--    <div class="quiz-wrapper">-->
<!--        <header class="quiz-header">-->
<!--            <h1 class="question-title">{{ question }}</h1>-->
<!--            <div class="timer">{{ timeLeft }}</div>-->
<!--        </header>-->

<!--        <div class="question-content">-->
<!--            <div class="image-container" v-if="image">-->
<!--                <img :src="image" alt="Question image" />-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="answers-grid">-->
<!--            <button-->
<!--                v-for="(answer, index) in answers"-->
<!--                :key="index"-->
<!--                :class="['answer-card', answer.color]"-->
<!--                @click="$emit('answerSelected', index)"-->
<!--            >-->
<!--                <span class="shape">{{ shapes[index] }}</span>-->
<!--                <span class="answer-text">{{ answer }}</span>-->
<!--            </button>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script setup>-->
<!--defineProps({-->
<!--    question: String,-->
<!--    image: String,-->
<!--    answers: Array,-->
<!--    timeLeft: Number-->
<!--})-->

<!--const shapes = ['▲', '◆', '●', '■']-->
<!--</script>-->

<!--<style scoped>-->
<!--.quiz-wrapper {-->
<!--    min-height: 100vh;-->
<!--    background: #121212;-->
<!--    color: white;-->
<!--    display: flex;-->
<!--    flex-direction: column;-->
<!--    padding: 20px;-->
<!--    box-sizing: border-box;-->
<!--}-->

<!--.quiz-header {-->
<!--    display: flex;-->
<!--    justify-content: space-between;-->
<!--    align-items: center;-->
<!--}-->

<!--.question-title {-->
<!--    font-size: 2rem;-->
<!--    font-weight: bold;-->
<!--}-->

<!--.timer {-->
<!--    font-size: 2rem;-->
<!--    background: #222;-->
<!--    padding: 10px 20px;-->
<!--    border-radius: 50%;-->
<!--}-->

<!--.question-content {-->
<!--    flex: 1;-->
<!--    display: flex;-->
<!--    justify-content: center;-->
<!--    align-items: center;-->
<!--}-->

<!--.image-container img {-->
<!--    max-height: 300px;-->
<!--    border-radius: 12px;-->
<!--}-->

<!--.answers-grid {-->
<!--    display: grid;-->
<!--    grid-template-columns: repeat(2, 1fr);-->
<!--    gap: 16px;-->
<!--}-->

<!--.answer-card {-->
<!--    display: flex;-->
<!--    align-items: center;-->
<!--    padding: 20px;-->
<!--    font-size: 1.2rem;-->
<!--    border: none;-->
<!--    border-radius: 12px;-->
<!--    cursor: pointer;-->
<!--    font-weight: bold;-->
<!--    color: white;-->
<!--}-->

<!--.shape {-->
<!--    font-size: 1.5rem;-->
<!--    margin-right: 12px;-->
<!--}-->

<!--.red { background: #e21b3c; }-->
<!--.blue { background: #1368ce; }-->
<!--.yellow { background: #d89e00; }-->
<!--.green { background: #26890c; }-->
<!--</style>-->



<!-- What chatgipiti said now: -->
<template>
    <div class="quiz-wrapper">
        <header class="quiz-header">
            <h1 class="question-title">{{ question }}</h1>
            <div class="timer">{{ timeLeft }}</div>
        </header>

        <div class="question-content">
            <div class="image-container" v-if="image">
                <img :src="image" alt="Question image" />
            </div>
        </div>

        <div class="answers-grid">
            <button
                v-for="(answer, index) in answers"
                :key="answer.id"
                :class="['answer-card', answer.color]"
                @click="$emit('answerSelected', answer)"
            >
                <span class="shape">{{ shapes[index] }}</span>
                <span class="answer-text">{{ answer.answer }}</span>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    question: String,
    image: String,
    answers: Array,
    timeLeft: Number
})

const shapes = ['▲', '◆', '●', '■']
</script>

<style scoped>
.quiz-wrapper {
    min-height: 100vh;
    background: #121212;
    color: white;
    display: flex;
    flex-direction: column;
    padding: 20px;
    box-sizing: border-box;
}

.quiz-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-title {
    font-size: 2rem;
    font-weight: bold;
}

.timer {
    font-size: 2rem;
    background: #222;
    padding: 10px 20px;
    border-radius: 50%;
}

.question-content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-container img {
    max-height: 300px;
    border-radius: 12px;
}

.answers-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.answer-card {
    display: flex;
    align-items: center;
    padding: 20px;
    font-size: 1.2rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: bold;
    color: white;
}

.shape {
    font-size: 1.5rem;
    margin-right: 12px;
}

.red { background: #e21b3c; }
.blue { background: #1368ce; }
.yellow { background: #d89e00; }
.green { background: #26890c; }
</style>
