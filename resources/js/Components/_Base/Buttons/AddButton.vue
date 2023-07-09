<template>
    <button type="button" class="btn btn-info" :class="{ 'btn-pill': pill, 'btn-sm': small }">
        <fa-icon icon="fa-plus" />
        <slot>
            Add
        </slot>
    </button>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
    small?: boolean;
}>();
</script>
