<template>
    <div
        class="grid grid-cols-1 md:grid-cols-4 items-center h-full md:h-screen mx-1 py-2"
    >
        <div class="md:col-span-2 md:col-start-2">
            <LogoImage>
                <h5 class="text-center my-3">
                    Enter your Email Address for instructions on recovering your
                    account
                </h5>
                <AuthFlash />
                <ForgotPasswordForm />
            </LogoImage>
        </div>
    </div>
</template>

<script setup lang="ts">
import LogoImage from "@/Components/_Base/LogoImage.vue";
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import ForgotPasswordForm from "@/Forms/Auth/ForgotPasswordForm.vue";
import AuthFlash from "@/Layouts/Auth/AuthFlash.vue";
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
