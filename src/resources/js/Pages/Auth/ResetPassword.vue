<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import FlashAlert from "@/Layouts/_Shared/FlashAlert.vue";
import LogoImage from "@/Components/LogoImage.vue";
import ResetPasswordForm from "@/Forms/Auth/ResetPasswordForm.vue";

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex flex-col items-center justify-center h-screen">
        <LogoImage>
            <ResetPasswordForm :email="email" :token="token" />
        </LogoImage>
        <Card title="Password Rules" class="tb-card-md">
            <ul class="text-center">
                <li v-for="rule in rules">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
