<template>
    <Head title="Forgot Password" />
    <div>
        <h6 class="text-center">
            Enter your Email Address for instructions on recovering your account
        </h6>
        <ForgotPasswordForm />
    </div>
</template>

<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import ForgotPasswordForm from "@/Forms/Auth/ForgotPasswordForm.vue";
</script>

<script lang="ts">
export default { layout: [GuestLayout, AuthLayout] };
</script>
