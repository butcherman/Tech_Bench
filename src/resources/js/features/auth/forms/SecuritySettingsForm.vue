<script setup lang="ts">
import Collapse from "@/core/components/Collapse.vue";
import DatePickerInput from "@/core/forms/components/validatedInputs/DatePickerInput.vue";
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import SelectInput from "@/core/forms/components/validatedInputs/SelectInput.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, boolean, string } from "yup";
import { update } from "@/wayfinder/routes/admin/user/user-settings";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    autoLogoutTimer: number;
    oath: OathConfig;
    roleList: UserRole[];
    twoFa: MultiFactorConfig;
}>();

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
        methods: object({
            email: boolean().required(),
            authenticator: boolean().required(),
        }),
    }),
    oath: object({
        allow_login: boolean().required(),
        allow_register: boolean().required(),
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
                    "You must enter the Expiration Date for the Client Secret",
                ),
            otherwise: (schema) => schema.nullable(),
        }),
        redirect: string().required(),
    }),
});
</script>

<template>
    <VueForm
        name="security-settings-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="put"
        submit-text="Update User Settings"
        do-not-reset
        v-slot="{ values }"
        @success="$emit('success')"
    >
        <fieldset class="border mb-3 py-3">
            <legend>Two Factor Authentication</legend>
            <div class="ms-4 flex flex-col gap-3">
                <SwitchInput
                    name="twoFa.enabled"
                    label="Enable Two-Factor Authentication"
                />

                <Collapse
                    :show="values.twoFa.enabled"
                    class="flex flex-col gap-3"
                >
                    <SwitchInput
                        id="require-2fa"
                        name="twoFa.required"
                        label="Require Two-Factor Authentication"
                    />
                    <SwitchInput
                        id="save-device"
                        name="twoFa.allow_save_device"
                        label="Allow Users to Save Devices for Future Login"
                    />
                    <SwitchInput
                        id="allow-via-email"
                        name="twoFa.methods.email"
                        label="Allow Email as Two Factor Method"
                    />
                    <SwitchInput
                        id="allow-via-authenticator"
                        name="twoFa.methods.authenticator"
                        label="Allow Authenticator App as Two Factor Method"
                    />
                </Collapse>
            </div>
        </fieldset>
        <fieldset class="border mb-3 py-3">
            <legend>Single Sign On</legend>
            <div class="ms-4 flex flex-col gap-3">
                <SwitchInput
                    id="allow-oath"
                    name="oath.allow_login"
                    label="Allow Office 365 Login"
                />
                <Collapse
                    :show="values.oath.allow_login"
                    class="flex flex-col gap-3"
                >
                    <SwitchInput
                        name="oath.allow_register"
                        class="w-100"
                        label="Allow anyone in my organization to login"
                    />
                    <SelectInput
                        name="oath.default_role_id"
                        label="User Role When Creating New User"
                        :list="roleList"
                        text-field="name"
                        value-field="role_id"
                    />
                    <TextInput name="oath.tenant" label="Azure Tenant ID" />
                    <TextInput name="oath.client_id" label="Azure Client ID" />
                    <PasswordInput
                        type="password"
                        name="oath.client_secret"
                        label="Azure Client Secret"
                        hide-unmask
                    />
                    <DatePickerInput
                        name="oath.secret_expires"
                        label="Date Client Secret Expires"
                    />
                    <TextInput
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
