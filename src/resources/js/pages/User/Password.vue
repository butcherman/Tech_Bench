<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import ChangePasswordForm from "@/features/user/forms/ChangePasswordForm.vue";

defineProps<{
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col justify-center items-center h-full">
        <Card title="Change Password" size="normal">
            <ChangePasswordForm />
        </Card>
        <Card title="Password Rules" size="normal">
            <ul class="text-center">
                <li v-for="rule in rules" class="border-b">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
