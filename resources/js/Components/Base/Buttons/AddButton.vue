<template>
    <button class="btn btn-info" :class="{ 'btn-pill': pill, 'btn-sm': small }">
        <fa-icon icon="fa-plus" />
        Add
    </button>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    pill?: boolean;
    small?: boolean;
}>();
</script>
