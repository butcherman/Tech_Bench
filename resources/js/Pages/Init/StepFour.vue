<template>
    <InitLayout :step-id="4">
        <div class="card-title">User Settings</div>
        <p class="text-center">
            Please define the password policy you would like to implement.
        </p>
        <p class="text-center">
            If you use Azure Active Directory, you can setup the ability for
            users to use their O365 login to access the Tech Bench as well.
        </p>
        <UserSettingsForm :policy="policy" @step-completed="nextStep" />
    </InitLayout>
</template>

<script setup lang="ts">
import InitLayout from "./InitLayout.vue";
import UserSettingsForm from '@/Forms/Admin/User/UserSettingsForm.vue';
import { router } from "@inertiajs/vue3";

type passwordPolicyType = {
    expire: number;
    min_length: number;
    contains_uppercase: boolean;
    contains_lowercase: boolean;
    contains_number: boolean;
    contains_special: boolean;
    allowOath: boolean;
    allowRegister: boolean;
    tenantId: string;
    clientId: string;
    clientSecret: string;
    redirectUri: string;
};

defineProps<{
    policy: passwordPolicyType;
}>();

const nextStep = () => {
    router.get(route("init.step-5"));
};
</script>
