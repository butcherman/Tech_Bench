<template>
    <button class="btn btn-warning mx-1" :class="{ 'btn-pill' : pill}">
        <fa-icon icon="edit" />
        Edit
    </button>
</template>

<script setup lang="ts">
    import { ref, reactive, onMounted } from 'vue';

    const props = defineProps<{
        pill?: boolean;
    }>();
</script>
