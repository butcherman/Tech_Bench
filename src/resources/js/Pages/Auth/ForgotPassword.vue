<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import ForgotPasswordForm from "@/Forms/Auth/ForgotPasswordForm.vue";
import LogoImage from "@/Components/LogoImage.vue";
import FlashAlert from "@/Layouts/_Shared/FlashAlert.vue";
import { ref } from "vue";

const success = ref(false);
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen">
        <FlashAlert />
        <LogoImage class="tb-gap">
            <div v-if="!success">
                <h5 class="text-center">
                    Enter your Email Address for instructions on recovering your
                    account
                </h5>
                <ForgotPasswordForm class="tb-gap" @success="success = true" />
            </div>
            <div v-else>
                <h5 class="text-center">
                    Please check your email for additional instructions.
                </h5>
            </div>
        </LogoImage>
    </div>
</template>
