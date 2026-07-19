<script setup lang="ts">
import LoginForm from "../forms/LoginForm.vue";

const props = defineProps<{
    allowOath: boolean;
}>();
</script>

<template>
    <div>
        <h3 class="text-center text-muted">Login</h3>
        <LoginForm :allow-oath="allowOath" />
    </div>
</template>
