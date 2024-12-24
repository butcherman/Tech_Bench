<template>
    <div class="grid gap-2 md:place-items-center">
        <Card class="reasonable-width">
            <UserPasswordForm />
        </Card>

        <Card class="reasonable-width">
            <h5 class="text-center font-bold">Password Rules</h5>
            <ul class="text-center">
                <li v-for="rule in rules" class="border p-2">{{ rule }}</li>
            </ul>
        </Card>
    </div>
</template>

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
