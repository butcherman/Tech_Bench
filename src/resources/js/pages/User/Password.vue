<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserPasswordForm from "@/features/user/forms/UserPasswordForm.vue";
import { useAlertState } from "@/core/state/alertState";

defineOptions({ layout: AppLayout });

const props = defineProps<{
    rules: string[];
}>();

const { pushFlashAlert } = useAlertState();

const onPasswordReset = () => {
    pushFlashAlert({ message: "Password Updated", variant: "success" });
};
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">Update Password</h1>
        </div>
        <div class="flex flex-col gap-2 items-center">
            <Card class="w-full lg:w-3/4">
                <UserPasswordForm @success="onPasswordReset" />
            </Card>
            <Card title="Password Rules" class="w-full lg:w-3/4">
                <ul class="text-center">
                    <li v-for="rule in rules">{{ rule }}</li>
                </ul>
            </Card>
        </div>
    </div>
</template>
