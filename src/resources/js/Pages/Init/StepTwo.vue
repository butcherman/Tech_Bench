<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
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

<template>
    <Card class="tb-card" title="Email Settings">
        <p class="text-center">
            The Tech Bench requires email settings in order to send notification
            emails. Please enter the proper email server information below.
        </p>
        <div>
            <EmailConfigForm
                class="w-full md:w-3/4 justify-self-center mt-3"
                :settings="settings"
                init
                @success="router.get($route('init.step-3'))"
            />
        </div>
    </Card>
</template>
