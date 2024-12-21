<template>
    <div
        class="w-full h-full grid grid-cols-1 content-center justify-items-center"
    >
        <div class="md:w-1/2"></div>
        <LogoImage class="md:w-1/2">
            <AuthFlash />
            <ResetPasswordForm class="mt-4" :email="email" :token="token" />
        </LogoImage>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import AuthFlash from "@/Layouts/Auth/AuthFlash.vue";
import LogoImage from "@/Components/Main/LogoImage.vue";
import ResetPasswordForm from "@/Forms/Auth/ResetPasswordForm.vue";

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
