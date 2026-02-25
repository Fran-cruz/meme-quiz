<template>
    <QuestionTemplate
        :question="question"
        :image="image"
        :answers="answers"
        :timeLeft="timeLeft"
        @answerSelected="handleAnswer"
    />
</template>

<script setup>
import QuestionTemplate from '@/Pages/Components/QuestionTemplate.vue';  // Import the renamed component

const question = "Pregunta?";
const answers = ['A1', 'A2', 'A3', 'A4'];
const timeLeft = 12;
const image = "Path/to/image.jpg";  // Define the image path

function handleAnswer(index) {
    console.log(`You selected: ${answers[index]}`);
}
</script>
