<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";
import ResetPasswordForm from "@/features/auth/forms/ResetPasswordForm.vue";
import { useAppData } from "@/core/state/appData";

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();

const { appName, logo } = useAppData();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
<template>
    <div class="flex flex-col justify-center items-center h-screen gap-3">
        <h1 class="text-center text-white">{{ appName }}</h1>
        <Card size="md">
            <div class="flex justify-center">
                <img :src="logo" />
            </div>
            <hr class="bg-gray-500 my-2" />
            <div>
                <ResetPasswordForm :email="email" :token="token" />
            </div>
        </Card>
        <Card title="Password Rules" size="sm">
            <ul class="text-center">
                <li v-for="rule in rules">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
