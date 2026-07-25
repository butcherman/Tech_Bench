<script setup lang="ts">
import ForgotPassword from "@/features/auth/components/ForgotPassword.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen">
        <ForgotPassword />
    </div>
</template>
