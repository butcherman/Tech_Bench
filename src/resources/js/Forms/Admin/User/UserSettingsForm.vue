<template>
    <VueForm
        ref="userSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.user.user-settings.update')"
        submit-method="put"
        submit-text="Update User Settings"
    >
        <fieldset class="border-bottom">
            <legend>Two Factor Authentication</legend>
            <div class="ms-4">
                <CheckboxSwitch
                    id="require-2fa"
                    name="twoFa.required"
                    label="Require Two-Factor Authentication"
                />
                <CheckboxSwitch
                    id="save-device"
                    name="twoFa.allow_save_device"
                    label="Allow Users to Save Devices for Future Login"
                    :disabled="disableTwoFaFields"
                />
            </div>
        </fieldset>
        <fieldset>
            <legend>Single Sign On</legend>
            <div class="ms-4">
                <CheckboxSwitch
                    id="allow-oath"
                    name="oath.allow_login"
                    label="Allow Office 365 Login"
                />
                <CheckboxSwitch
                    id="oath_register"
                    name="oath.allow_register"
                    class="w-100"
                    label="Allow anyone in my organization to login"
                    :disabled="disableOathFields"
                />
                <CheckboxSwitch
                    id="two_fa_bypass"
                    name="oath.allow_bypass_2fa"
                    class="w-100"
                    label="Allow Single Sign On Users to Bypass Two-Factor Authentication"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-tenant-id"
                    name="oath.tenant"
                    label="Azure Tenant ID"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-client-id"
                    name="oath.client_id"
                    label="Azure Client ID"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-client-secret"
                    type="password"
                    name="oath.client_secret"
                    label="Azure Client Secret"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-secret-expiration"
                    name="oath.secret_expires"
                    label="Date Client Secret Expires"
                    type="date"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-redirect"
                    type="url"
                    name="oath.redirectUri"
                    label="Azure Redirect URI"
                    disabled
                />
            </div>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { ref, computed } from "vue";
import { boolean, object, string } from "yup";

const props = defineProps<{
    twoFa: twoFaConfig;
    oath: oathConfig;
}>();

const userSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);

const disableTwoFaFields = computed(
    () => !userSettingsForm.value?.getFieldValue("twoFa").required
);
const disableOathFields = computed(
    () => !userSettingsForm.value?.getFieldValue("oath").allow_login
);

const initValues = {
    twoFa: props.twoFa,
    oath: props.oath,
};
const schema = object({
    twoFa: object({
        required: boolean().required(),
        allow_save_device: boolean().required(),
    }),
    oath: object({
        allow_login: boolean().required(),
        allow_register: boolean().required(),
        allow_bypass_2fa: boolean().required(),
        tenant: string().when("allow_login", {
            is: true,
            then: (schema) =>
                schema.required("You must enter the Azure Tenant ID"),
            otherwise: (schema) => schema.nullable(),
        }),
        client_id: string().when("allow_login", {
            is: true,
            then: (schema) =>
                schema.required("You must enter the Azure Client ID"),
            otherwise: (schema) => schema.nullable(),
        }),
        client_secret: string().when("allow_login", {
            is: true,
            then: (schema) =>
                schema.required("You must enter the Azure Client Secret"),
            otherwise: (schema) => schema.nullable(),
        }),
        secret_expires: string().when("allow_login", {
            is: true,
            then: (schema) =>
                schema.required(
                    "You must enter the Expiration Date for the Client Secret"
                ),
            otherwise: (schema) => schema.nullable(),
        }),
        redirectUri: string().required(),
    }),
});
</script>
