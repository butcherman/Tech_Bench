<script setup lang="ts">
import UserPasswordForm from "../forms/UserPasswordForm.vue";

const props = defineProps<{
    passwordRules: string[];
}>();
</script>

<template>
    <div>
        <h5 class="text-center text-muted">Change Password</h5>
        <UserPasswordForm />
        <div class="mt-2">
            <ul class="text-center">
                <li v-for="rule in passwordRules">{{ rule }}</li>
            </ul>
        </div>
    </div>
</template>
