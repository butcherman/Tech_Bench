<template>
    <h6 class="text-center">New Message From {{ from }}</h6>
    <div class="text-center">
        {{ message }}
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    from: string;
    message: string;
}>();
</script>
