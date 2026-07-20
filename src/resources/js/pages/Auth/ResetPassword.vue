<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import ResetPassword from "@/features/auth/components/ResetPassword.vue";

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen">
        <ResetPassword :email="email" :token="token" :rules="rules" />
    </div>
</template>
