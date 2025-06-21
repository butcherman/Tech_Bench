<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import UserPasswordForm from "@/Forms/User/UserPasswordForm.vue";

defineProps<{
    rules: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex flex-col grow justify-center items-center">
        <Card class="tb-card">
            <UserPasswordForm />
        </Card>
        <Card class="tb-card-md" title="Password Rules">
            <ul class="text-center">
                <li v-for="rule in rules" class="border-b">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>
