<template>
    <div class="loading">{{ text }}</div>
</template>

<script setup lang="ts">
defineProps<{
    text?: string;
}>();
</script>

<style scoped>
.loading {
    font-size: 30px;
}

.loading:after {
    overflow: hidden;
    display: inline-block;
    vertical-align: bottom;
    -webkit-animation: ellipsis steps(4, end) 900ms infinite;
    animation: ellipsis steps(4, end) 900ms infinite;
    content: "\2026"; /* ascii code for the ellipsis character */
    width: 0px;
}

@keyframes ellipsis {
    to {
        width: 1.25em;
    }
}

@-webkit-keyframes ellipsis {
    to {
        width: 1.25em;
    }
}
</style>
