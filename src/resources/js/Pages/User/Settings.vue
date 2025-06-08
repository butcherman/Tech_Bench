<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AuthenticatorAppSetup from "@/Components/User/AuthenticatorAppSetup.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import UserSettingsForm from "@/Forms/User/UserSettingsForm.vue";
import { router } from "@inertiajs/vue3";
import { ref, useTemplateRef } from "vue";
import { dataGet } from "@/Composables/axiosWrapper.module";

const props = defineProps<{
    current_user: user;
    allowSaveDevice: boolean;
    devices: userDevice[];
    settings: userSettings[];
    allowViaAuthenticator: boolean;
}>();

const twoFaModal = useTemplateRef("two-fa-setup-modal");
const twoFaForm = useTemplateRef("two-fa-setup-form");
const twoFaCodes = useTemplateRef("two-fa-recovery-codes");

/**
 * Open the Two FA form and start process for setting authenticator app
 */
const onSetupTwoFa = () => {
    twoFaModal.value?.show();
    twoFaForm.value?.triggerSetup();
};

/**
 * Open the Two FA Recovery Codes modal and fetch the codes.
 */
const recoveryCode = ref<StringConstructor>();
const onGetRecoveryCodes = () => {
    dataGet(route("two-factor.secret-key")).then((res) => {
        if (res) {
            console.log(res);
            recoveryCode.value = res.data.secretKey;
            twoFaCodes.value?.show();
        }
    });
};

/**
 * Delete a saved device from the 2FA device list.
 */
const deleteDevice = (deviceData: userDevice) => {
    router.delete(
        route("user.remove-device", [
            props.current_user.username,
            deviceData.device_id,
        ]),
        {
            preserveScroll: true,
        }
    );
};

const tableHeaders = [
    {
        label: "Device Type",
        field: "type",
    },
    {
        label: "Device OS",
        field: "os",
    },
    {
        label: "Browser Used",
        field: "browser",
    },
    {
        label: "Last Successful Login",
        field: "updated_at",
    },
    {
        label: "Registration Date",
        field: "created_at",
    },
    {
        field: "action",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <Card title="My Account">
            <UserAccountForm :user="current_user" />
        </Card>
        <Card title="My Settings">
            <UserSettingsForm :user="current_user" :settings="settings" />
        </Card>
        <Card
            v-if="allowSaveDevice"
            title="Two Factor Authentication"
            class="md:col-span-2"
        >
            <template #append-title>
                <BaseButton
                    v-if="
                        allowViaAuthenticator &&
                        current_user.two_factor_confirmed_at === null
                    "
                    text="Setup Authenticator App"
                    size="small"
                    pill
                    @click="onSetupTwoFa()"
                />
                <BaseButton
                    v-if="
                        allowViaAuthenticator &&
                        current_user.two_factor_confirmed_at !== null
                    "
                    text="Show Authenticator Recovery Codes"
                    size="small"
                    pill
                    @click="onGetRecoveryCodes()"
                />
            </template>
            <p class="text-center">
                The following devices have been verified and saved as trusted
                devices.
            </p>
            <p class="text-center">
                A trusted device allows you to skip the two-factor
                authentication for 180 days from the registration date.
            </p>
            <hr class="mb-2" />
            <DataTable
                :columns="tableHeaders"
                :rows="devices"
                sync-loading-state
            >
                <template #row.action="{ rowData }">
                    <DeleteBadge
                        v-tooltip.left="'Delete Device'"
                        confirm-msg="Are you sure you want to remove this device?"
                        confirm
                        @accepted="deleteDevice(rowData)"
                    />
                </template>
            </DataTable>
        </Card>
        <Modal ref="two-fa-setup-modal" title="Setup Authenticator App">
            <AuthenticatorAppSetup
                ref="two-fa-setup-form"
                @success="twoFaModal?.hide()"
            />
        </Modal>
        <Modal ref="two-fa-recovery-codes">
            <h3 class="text-center">
                Store this code in a safe location, it can be used to recover
                your account if your Authenticator App is lost.
            </h3>
            <h5 class="text-center text-danger">
                {{ recoveryCode }}
                <ClipboardCopy :value="recoveryCode" />
            </h5>
        </Modal>
    </div>
</template>
