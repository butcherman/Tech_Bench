<script setup lang="ts">
import AuthenticatorAppSetup from "./AuthenticatorAppSetup.vue";
import BaseButton from "../_Base/Buttons/BaseButton.vue";
import Modal from "../_Base/Modal.vue";
import TwoFactorForm from "@/Forms/Auth/TwoFactorForm.vue";
import { dataGet } from "@/Composables/axiosWrapper.module";
import { router } from "@inertiajs/vue3";
import { useTemplateRef } from "vue";

defineProps<{
    twoFa: {
        allowSaveDevice: boolean;
        allowAuthenticator: boolean;
        allowEmail: boolean;
        currentVia: "email" | "authenticator" | null;
        devices: userDevice[];
        enabled: boolean;
    };
}>();

const twoFaModal = useTemplateRef("setup-authenticator-modal");
const twoFaForm = useTemplateRef("setup-authenticator-form");
const twoFaEmailModal = useTemplateRef("setup-email-modal");

/**
 * Set Authenticator App as the primary option for getting 2FA Code
 */
const assignTwoFaViaAuthenticator = () => {
    twoFaModal.value?.show();
    twoFaForm.value?.triggerSetup();
};

/**
 * Close out the Authenticator App Setup
 */
const onSetupSuccess = () => {
    twoFaModal.value?.hide();
    router.reload();
};

/**
 * Set Email as the primary option for getting 2FA Code
 */
const assignTwoFaViaEmail = () => {
    dataGet(route("two-factor.setup.email")).then((res) => {
        twoFaEmailModal.value?.show();
    });
};
</script>

<template>
    <div class="flex flex-col md:flex-row gap-3">
        Get Two Factor Authentication Codes from
        <div class="grow flex gap-2">
            <BaseButton
                class="flex-1"
                text="Authenticator App"
                :variant="
                    twoFa.currentVia === 'authenticator' ? 'success' : 'light'
                "
                @click="assignTwoFaViaAuthenticator()"
            />
            <BaseButton
                class="flex-1"
                text="Email"
                :variant="twoFa.currentVia === 'email' ? 'success' : 'light'"
                @click="assignTwoFaViaEmail()"
            />
        </div>
        <Modal ref="setup-authenticator-modal">
            <AuthenticatorAppSetup
                ref="setup-authenticator-form"
                @success="onSetupSuccess()"
            />
        </Modal>
        <Modal ref="setup-email-modal">
            <h5 class="text-center">
                A verification code has been sent to your email address.
            </h5>
            <p class="text-center">Please input the code below.</p>
            <TwoFactorForm :allow-remember="false" via="email" />
        </Modal>
    </div>
</template>
