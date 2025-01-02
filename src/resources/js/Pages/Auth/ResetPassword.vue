<template>
    <div>
        <div
            class="grid grid-cols-1 md:grid-cols-4 items-center h-full md:h-screen mx-1 py-1"
        >
            <div class="md:col-span-2 md:col-start-2">
                <LogoImage>
                    <AuthFlash />
                    <ResetPasswordForm :email="email" :token="token" />
                </LogoImage>
            </div>
            <div class="md:col-span-2 md:col-start-2 mt-2">
                <Card title="Password Rules">
                    <ul class="text-center">
                        <li v-for="rule in rules">{{ rule }}</li>
                    </ul>
                </Card>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import AuthFlash from "@/Layouts/Auth/AuthFlash.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
import ResetPasswordForm from "@/Forms/Auth/ResetPasswordForm.vue";
import Card from "@/Components/_Base/Card.vue";

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
