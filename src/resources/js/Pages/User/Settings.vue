<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AuthenticatorRecoveryCodes from "@/Components/User/AuthenticatorRecoveryCodes.vue";
import AuthenticatorViaConfig from "@/Components/User/AuthenticatorViaConfig.vue";
import Card from "@/Components/_Base/Card.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import UserSettingsForm from "@/Forms/User/UserSettingsForm.vue";
import VerifiedDevices from "@/Components/User/VerifiedDevices.vue";
import { computed } from "vue";

const props = defineProps<{
    current_user: user;
    settings: userSettings[];
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        devices: userDevice[];
        enabled: boolean;
    };
}>();

/**
 * Determine if we should show the Two Factor settings box
 */
const showTwoFaBox = computed<boolean>(() => {
    if (!props.twoFa.enabled) {
        return false;
    }

    if (props.twoFa.allowSaveDevice) {
        return true;
    }

    if (props.twoFa.allowAuthenticator && props.twoFa.allowEmail) {
        return true;
    }

    return false;
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="flex flex-col md:flex-row gap-3">
            <Card class="flex-1" title="My Account">
                <UserAccountForm :user="current_user" />
            </Card>
            <Card class="flex-1" title="My Settings">
                <UserSettingsForm :user="current_user" :settings="settings" />
            </Card>
        </div>
        <div v-if="showTwoFaBox">
            <Card title="Two Factor Authentication">
                <template #append-title>
                    <AuthenticatorRecoveryCodes />
                </template>
                <div
                    v-if="twoFa.allowAuthenticator && twoFa.allowEmail"
                    class="border-b border-b-slate-300 pb-3 mb-3"
                >
                    <AuthenticatorViaConfig :two-fa="twoFa" />
                </div>
                <VerifiedDevices
                    v-if="twoFa.allowSaveDevice"
                    :devices="twoFa.devices"
                    :current_user="current_user"
                />
            </Card>
        </div>
    </div>
</template>
