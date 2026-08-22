<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";
import ForgotPasswordForm from "@/features/auth/forms/ForgotPasswordForm.vue";
import { login } from "@/wayfinder/routes";
import { ref } from "vue";
import { useAppData } from "@/core/state/appData";

const { appName, logo } = useAppData();
const success = ref<boolean>(false);
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
<template>
    <div class="flex flex-col justify-center items-center h-screen">
        <h1 class="text-center text-white">{{ appName }}</h1>
        <Card size="md">
            <div class="flex justify-center">
                <img :src="logo" />
            </div>
            <hr class="bg-gray-500 my-2" />
            <div v-if="!success">
                <h5 class="text-center text-muted">
                    Enter your Email Address for instructions on recovering your
                    account
                </h5>
                <div class="flex justify-center">
                    <ForgotPasswordForm
                        class="w-full md:w-3/4"
                        @success="success = true"
                    />
                </div>
            </div>
            <div v-else>
                <h5 class="text-center">
                    Please check your email for additional instructions.
                </h5>
                <p class="text-center text-blue-400">
                    <Link :href="login">Return to Login Page</Link>
                </p>
            </div>
        </Card>
    </div>
</template>
