<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import TwoFactorSettings from "@/features/user/components/TwoFactorSettings.vue";
import UserSettings from "@/features/user/components/UserSettings.vue";
import { computed } from "vue";

const props = defineProps<{
    current_user: User;
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

const showTwoFa = computed(() => {
    return props.twoFa.enabled && props.twoFa.currentVia;
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-3">
        <UserSettings :user="current_user" :settings="settings" />
        <TwoFactorSettings
            v-if="showTwoFa"
            :user="current_user"
            :devices="twoFa.devices"
        />
    </div>
</template>
