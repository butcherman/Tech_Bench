<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";
import ResetPasswordForm from "@/features/auth/forms/ResetPasswordForm.vue";

defineOptions({ layout: AuthLayout });

defineProps<{
    email: string;
    token: string;
    rules: string[];
}>();
</script>

<template>
    <div class="w-full lg:w-96 mt-2 flex flex-col gap-4">
        <ResetPasswordForm :email :token />
        <Card title="Password Rules">
            <ul class="text-center">
                <li v-for="rule in rules">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
