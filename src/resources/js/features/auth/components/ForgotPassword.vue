<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogoImage from "@/core/components/LogoImage.vue";
import ForgotPasswordForm from "../forms/ForgotPasswordForm.vue";
import { ref } from "vue";
import { login } from "@/wayfinder/routes/index.js";

const success = ref<boolean>(false);
</script>

<template>
    <Card>
        <LogoImage />
        <h5 class="text-center text-muted">
            Enter your email address for instructions on recovering your
            account.
        </h5>
        <ForgotPasswordForm @success="success = true" />
        <div v-if="success">
            <h5 class="text-center">
                Please check your email for additional instructions.
            </h5>
            <p class="text-center text-blue-400">
                <Link :href="login.url()">Return to Login Page</Link>
            </p>
        </div>
    </Card>
</template>
