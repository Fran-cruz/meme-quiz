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

const question = "Tu mama?";
const answers = ['Si', 'No', 'Quizas', 'si'];
const timeLeft = 12;
const image = "https://heightline.com/wp-content/uploads/maxresdefault-102-696x392.jpg";  // Define the image path

function handleAnswer(index) {
    console.log(`You selected: ${answers[index]}`);
}
</script>
