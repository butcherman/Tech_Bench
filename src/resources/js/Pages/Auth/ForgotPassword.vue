<template>
    <div
        class="w-full h-full grid grid-cols-1 content-center justify-items-center"
    >
        <div class="md:w-1/2"></div>
        <LogoImage class="md:w-1/2">
            <h5 class="text-center my-3">
                Enter your Email Address for instructions on recovering your
                account
            </h5>
            <AuthFlash />
            <ForgotPasswordForm class="mt-4" />
        </LogoImage>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import AuthFlash from "@/Layouts/Auth/AuthFlash.vue";
import LogoImage from "@/Components/Main/LogoImage.vue";
import ForgotPasswordForm from "@/Forms/Auth/ForgotPasswordForm.vue";
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
