<template>
    <VueForm
        ref="userSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.user.user-settings.update')"
        submit-method="put"
        submit-text="Update User Settings"
    >
        <TextInput
            id="auto-logout"
            name="auto_logout_timer"
            label="User Idle Log Out Timer (in Minutes)"
            :help="`If the user is idle for more than this timer, the system
                    will automatically log them out`"
        />
        <fieldset class="border-bottom">
            <legend>Two Factor Authentication</legend>
            <div class="ms-4">
                <CheckboxSwitch
                    id="require-2fa"
                    name="twoFa.required"
                    label="Require Two-Factor Authentication"
                />
                <Collapse :visible="!disableTwoFaFields">
                    <CheckboxSwitch
                        id="save-device"
                        name="twoFa.allow_save_device"
                        label="Allow Users to Save Devices for Future Login"
                        :disabled="disableTwoFaFields"
                    />
                </Collapse>
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
                <Collapse :visible="!disableOathFields">
                    <CheckboxSwitch
                        id="oath_register"
                        name="oath.allow_register"
                        class="w-100"
                        label="Allow anyone in my organization to login"
                        :disabled="disableOathFields"
                    />
                    <Collapse :visible="!hideDefaultRoleField">
                        <SelectInput
                            id="default_role_id"
                            name="oath.default_role_id"
                            label="User Role When Creating New User"
                            :list="roleList"
                            text-field="name"
                            value-field="role_id"
                        />
                    </Collapse>
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
                        name="oath.redirect"
                        label="Azure Redirect URI"
                        disabled
                    />
                </Collapse>
            </div>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import Collapse from "@/Components/_Base/Collapse.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import { ref, computed } from "vue";
import { boolean, object, string } from "yup";

const props = defineProps<{
    autoLogoutTimer: number;
    twoFa: twoFaConfig;
    oath: oathConfig;
    roleList: userRole[];
}>();

const userSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);

const disableTwoFaFields = computed(
    () => !userSettingsForm.value?.getFieldValue("twoFa").required
);
const disableOathFields = computed(
    () => !userSettingsForm.value?.getFieldValue("oath").allow_login
);
const hideDefaultRoleField = computed(
    () => !userSettingsForm.value?.getFieldValue("oath").allow_register
);

const initValues = {
    auto_logout_timer: props.autoLogoutTimer,
    twoFa: props.twoFa,
    oath: props.oath,
};
const schema = object({
    auto_logout_timer: string().required().label("Auto Logout Timer"),
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
        redirect: string().required(),
    }),
});
</script>
