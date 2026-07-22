<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserAccountForm from "@/features/user/forms/UserAccountForm.vue";

const props = defineProps<{
    current_user: User;
    settings: UserSettings[];
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        // devices: userDevice[];
        enabled: boolean;
    };
}>();
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
                <!-- <UserSettingsForm :user="current_user" :settings="settings" /> -->
            </Card>
        </div>
        <div>
            <Card title="Two Factor Authentication">
                <template #append-title>
                    <!-- <AuthenticatorRecoveryCodes /> -->
                </template>
                <div
                    v-if="twoFa.allowAuthenticator && twoFa.allowEmail"
                    class="border-b border-b-slate-300 pb-3 mb-3"
                >
                    <!-- <AuthenticatorViaConfig :two-fa="twoFa" /> -->
                </div>
                <!-- <VerifiedDevices
                    v-if="twoFa.allowSaveDevice"
                    :current_user="current_user"
                /> -->
            </Card>
        </div>
    </div>
</template>
