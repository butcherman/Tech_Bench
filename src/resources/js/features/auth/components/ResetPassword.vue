<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogoImage from "@/core/components/LogoImage.vue";
import ResetPasswordForm from "../forms/ResetPasswordForm.vue";

const props = defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<template>
    <Card>
        <LogoImage />
        <ResetPasswordForm :email="email" :token="token" />
        <Card title="Password Rules" class="mt-3">
            <ul class="text-center">
                <li v-for="rule in rules">{{ rule }}</li>
            </ul>
        </Card>
    </Card>
</template>
