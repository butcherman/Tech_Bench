<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserPasswordForm from "@/features/user/forms/UserPasswordForm.vue";

const props = defineProps<{
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-2">
        <Card>
            <UserPasswordForm />
        </Card>
        <Card title="Password Rules">
            <ul class="text-center">
                <li v-for="rule in rules">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
