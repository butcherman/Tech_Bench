<script setup lang="ts">
/**
 * Note:  Atom Spinner code compliments of Epic Spinners
 * https://epic-spinners.epicmax.co/
 */
defineProps<{
    text?: string;
}>();
</script>

<template>
    <div>
        <div class="atom-spinner mx-auto">
            <div class="spinner-inner">
                <div class="spinner-line"></div>
                <div class="spinner-line"></div>
                <div class="spinner-line"></div>
                <div class="spinner-circle">&#9679;</div>
            </div>
        </div>
        <h5 v-if="text" class="text-center text-muted">{{ text }}</h5>
    </div>
</template>

<style scoped>
.atom-spinner,
.atom-spinner * {
    box-sizing: border-box;
}

.atom-spinner {
    height: 60px;
    width: 60px;
    overflow: hidden;
}

.atom-spinner .spinner-inner {
    position: relative;
    display: block;
    height: 100%;
    width: 100%;
}

.atom-spinner .spinner-circle {
    display: block;
    position: absolute;
    color: #ff1d5e;
    font-size: calc(60px * 0.24);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.atom-spinner .spinner-line {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    animation-duration: 1s;
    border-left-width: calc(60px / 25);
    border-top-width: calc(60px / 25);
    border-left-color: #ff1d5e;
    border-left-style: solid;
    border-top-style: solid;
    border-top-color: transparent;
}

.atom-spinner .spinner-line:nth-child(1) {
    animation: atom-spinner-animation-1 1s linear infinite;
    transform: rotateZ(120deg) rotateX(66deg) rotateZ(0deg);
}

.atom-spinner .spinner-line:nth-child(2) {
    animation: atom-spinner-animation-2 1s linear infinite;
    transform: rotateZ(240deg) rotateX(66deg) rotateZ(0deg);
}

.atom-spinner .spinner-line:nth-child(3) {
    animation: atom-spinner-animation-3 1s linear infinite;
    transform: rotateZ(360deg) rotateX(66deg) rotateZ(0deg);
}

@keyframes atom-spinner-animation-1 {
    100% {
        transform: rotateZ(120deg) rotateX(66deg) rotateZ(360deg);
    }
}

@keyframes atom-spinner-animation-2 {
    100% {
        transform: rotateZ(240deg) rotateX(66deg) rotateZ(360deg);
    }
}

@keyframes atom-spinner-animation-3 {
    100% {
        transform: rotateZ(360deg) rotateX(66deg) rotateZ(360deg);
    }
}
</style>
