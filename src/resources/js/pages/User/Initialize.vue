<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";
import ResetPasswordForm from "@/features/auth/forms/ResetPasswordForm.vue";
import { useAppData } from "@/core/state/appData";

defineOptions({ layout: AuthLayout });

defineProps<{
    user: User;
    token: string;
    rules: string[];
}>();

const { appName } = useAppData();
</script>

<template>
    <div>
        <h1 class="text-center text-white">{{ appName }}</h1>
        <p class="text-center">
            Create a password to finish setting up your account.
        </p>
        <hr class="bg-gray-500 my-2" />
        <div>
            <ResetPasswordForm :email="user.email" :token="token" init />
        </div>
        <Card title="Password Rules" class="mt-4">
            <ul class="text-center">
                <li v-for="rule in rules">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
