<template>
    <button type="submit" class="btn btn-primary w-100" :disabled="submitted">
        <span v-if="submitted" class="spinner-border spinner-border-sm" />
        {{ text ? text : 'Submit' }}
    </button>
</template>

<script setup lang="ts">
    defineProps<{
        text     ?: string;
        submitted?: boolean;
    }>();
</script>
