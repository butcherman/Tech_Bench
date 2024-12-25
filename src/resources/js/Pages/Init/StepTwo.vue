<template>
    <div class="text-center">
        <h1 class="font-bold text-2xl">Email Settings</h1>
        <p>
            The Tech Bench requires email settings in order to send notification
            emails. Please enter the proper email server information below.
        </p>
        <EmailConfigForm
            :settings="settings"
            init
            @success="router.get($route('init.step-3'))"
        />
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    settings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
