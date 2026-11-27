<script setup lang="ts">
import BooleanBadge from "@/core/components/badges/BooleanBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { computed } from "vue";
import { index } from "@/wayfinder/routes/two-factor/setup";

const props = defineProps<{
    current_user: User;
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        devices: UserDevice[];
        enabled: boolean;
    };
}>();

const mfaStatus = computed(() => (props.twoFa.currentVia ? true : false));
</script>

<template>
    <div>
        <p class="text-center">
            Protect your account with an additional authentication method.
        </p>
        <h6 class="text-muted mt-4">MFA Status</h6>
        <div>
            <BooleanBadge :value="mfaStatus" />
            {{ mfaStatus ? "Enabled" : "Disabled" }}
        </div>
        <div v-if="mfaStatus">maintain mfa...</div>
        <div v-else>
            <BaseButton
                class="mt-4"
                size="sm"
                text="Setup MFA"
                variant="warning"
                :href="index.url()"
            />
        </div>
    </div>
</template>
