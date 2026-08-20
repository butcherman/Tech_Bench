<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Modal from "@/core/components/Modal.vue";
import { computed, ref } from "vue";
import { dataGet } from "@/core/services/axiosWrapper";
import { index } from "@/wayfinder/routes/two-factor/setup";
import { reset } from "@/wayfinder/routes/admin/user/two-factor";
import { router } from "@inertiajs/vue3";
import { recoveryCodes } from "@/wayfinder/routes/two-factor";

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

const statusIcon = computed(() => (props.twoFa.currentVia ? "check" : "xmark"));
const statusText = computed(() =>
    props.twoFa.currentVia ? "Enabled" : "Disabled",
);
const statusVariant = computed(() =>
    props.twoFa.currentVia ? "text-success" : "text-danger",
);

const onResetMfa = () => {
    router.put(reset.url(props.current_user.username));
};

const showRecoveryModal = ref<boolean>(false);
const recoveryCodeList = ref<string[]>([]);
const onShowRecoveryCodes = async () => {
    let code = await dataGet<string[]>(recoveryCodes.url());

    if (code) {
        recoveryCodeList.value = code;
        showRecoveryModal.value = true;
    }
};

const savedDeviceCount = computed(() => props.twoFa.devices.length);
</script>

<template>
    <div>
        <p class="text-center">
            Protect your account with an additional authentication method.
        </p>
        <h6 class="text-muted mt-4">2FA Status</h6>
        <div>
            <fa-icon :icon="statusIcon" :class="statusVariant" />
            {{ statusText }}
        </div>
        <div v-if="twoFa.currentVia">
            <div>
                You are currently using {{ twoFa.currentVia }} as your MFA
                Method.
            </div>
            <div class="mt-4">
                <BaseButton
                    text="Reset MFA"
                    size="sm"
                    variant="info"
                    @click="onResetMfa"
                />
            </div>
            <div v-if="twoFa.currentVia === 'authenticator'" class="mt-4">
                <h6 class="text-muted">Recovery Codes</h6>
                <BaseButton
                    text="View Recovery Codes"
                    size="sm"
                    variant="info"
                    @click="onShowRecoveryCodes"
                />
            </div>
            <div v-if="twoFa.allowSaveDevice" class="mt-4">
                <h6 class="text-muted">Saved Devices</h6>
                <div>{{ savedDeviceCount }} devices are currently trusted.</div>
            </div>
        </div>
        <div v-else>
            <BaseButton
                text="Setup MFA"
                size="sm"
                class="mt-4"
                variant="warning"
                :href="index.url()"
            />
        </div>
        <Modal
            v-model:show="showRecoveryModal"
            title="Store these Recovery Codes in a safe place"
        >
            <div v-for="code in recoveryCodeList" :key="code">
                {{ code }}
            </div>
        </Modal>
    </div>
</template>
