<template>
    <InitLayout :step-id="3">
        <div class="card-title">Email Settings</div>
        <p class="text-center">
            The Tech Bench requires email settings in order to send notification
            emails. Please enter the proper email server information below.
        </p>
        <EmailForm :settings="settings" />
        <Link :href="$route('init.step-4')" class="btn btn-primary w-100">
            Next Step
        </Link>
    </InitLayout>
</template>

<script setup lang="ts">
import InitLayout from "./InitLayout.vue";
import EmailForm from "@/Forms/Admin/EmailForm.vue";

interface emSettingsType {
    host: string;
    port: number;
    encryption: string;
    username: string;
    password: string;
    from_address: string;
    requireAuth: boolean;
}

const $route = route;
defineProps<{
    settings: emSettingsType;
}>();
</script>
