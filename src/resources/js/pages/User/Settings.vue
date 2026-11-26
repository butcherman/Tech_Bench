<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import UserSettingsWrapper from "@/features/user/components/UserSettingsWrapper.vue";
import { useAppData } from "@/core/state/appData";

defineOptions({ layout: AppLayout });

const props = defineProps<{
    current_user: User;
    passwordRules: string[];
    settings: UserSettings[];
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        devices: UserDevice[];
        enabled: boolean;
    };
}>();

const { appName } = useAppData();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 mb-3">
            <h1>User Settings</h1>
            <p>Manage your acocunt, security and {{ appName }} preferences.</p>
        </div>
        <UserSettingsWrapper v-bind="props" />
    </div>
</template>
