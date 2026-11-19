<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import ForgotPasswordForm from "@/features/auth/forms/ForgotPasswordForm.vue";
import { login } from "@/wayfinder/routes";
import { useAlertState } from "@/core/state/alertState";

defineOptions({ layout: AuthLayout });

const { pushFlashAlert } = useAlertState();

const onSuccess = () => {
    let alert: FlashAlert = {
        message: "Please check your email for reset instructions",
        variant: "success",
    };

    pushFlashAlert(alert);
};
</script>
<template>
    <div class="w-full lg:w-96">
        <div class="flex flex-col gap-4">
            <h2>Reset Your Password</h2>
            <p>
                Enter the email address associated with your account. We will
                send you instructions to reset your password.
            </p>
            <ForgotPasswordForm @success="onSuccess" />
            <p class="text-center text-blue-400">
                <Link :href="login.url()">
                    <fa-icon icon="arrow-left" />
                    Back to Sign In
                </Link>
            </p>
        </div>
    </div>
</template>
