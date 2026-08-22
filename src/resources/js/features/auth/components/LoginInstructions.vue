<script setup lang="ts">
import LoginForm from "../forms/LoginForm.vue";
import { azureLogin } from "@/wayfinder/routes/index.js";

const props = defineProps<{
    allowOath: boolean;
}>();
</script>

<template>
    <div>
        <h3 class="text-center text-muted">Login</h3>
        <LoginForm :allow-oath="allowOath" />
        <div v-if="allowOath" class="text-center">
            <div class="separator">or</div>
            <a :href="azureLogin.url()" class="w-1/2 text-blue-800">
                Use Single Sign-On
            </a>
        </div>
    </div>
</template>
