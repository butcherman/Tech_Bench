<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import Modal from "@/core/components/Modal.vue";
import TwoFactorForm from "@/features/auth/forms/TwoFactorForm.vue";
import TwoFactorRecoveryCodeForm from "../forms/TwoFactorRecoveryCodeForm.vue";
import { computed, ref } from "vue";
import { store } from "@/wayfinder/routes/two-factor/login";
import { verify } from "@/wayfinder/routes/two-factor";

const props = defineProps<{
    allowRemember: boolean;
    via: MultiFactorMethod;
}>();

const showRecoveryCode = ref(false);

const mfaText = computed(() => {
    return {
        email: "A verification code has been sent to your email",
        authenticator:
            "Please input the verification code from your Authenticator App",
    }[props.via];
});

const submitRoute = computed(() => {
    return {
        authenticator: store.url(),
        email: verify.url(),
    }[props.via];
});
</script>

<template>
    <div>
        <Card>
            <h5 class="text-center">{{ mfaText }}</h5>
            <div>
                <TwoFactorForm
                    :allow-remember="allowRemember"
                    :submit-route="submitRoute"
                />
            </div>
            <div
                v-if="via === 'authenticator'"
                class="text-center text-muted mt-4"
            >
                Don't have access to the Authenticator App? Use a
                <span
                    class="text-blue-400 hover:font-bold pointer"
                    @click="showRecoveryCode = true"
                >
                    Recovery Code
                </span>
                to login.
            </div>
        </Card>
        <Modal
            v-model:show="showRecoveryCode"
            title="MFA Recovery Code"
            size="sm"
        >
            <TwoFactorRecoveryCodeForm />
        </Modal>
    </div>
</template>
