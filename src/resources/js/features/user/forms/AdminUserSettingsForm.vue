<script setup lang="ts">
import Collapse from "@/core/components/Collapse.vue";
import DatePickerInput from "@/core/forms/components/DatePickerInput.vue";
import SelectInput from "@/core/forms/components/SelectInput.vue";
import SwitchInput from "@/core/forms/components/SwitchInput.vue";
import TextInput from "@/core/forms/components/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { computed, useTemplateRef } from "vue";
import { object, string, boolean } from "yup";
import { update } from "@/wayfinder/routes/admin/user/user-settings";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    autoLogoutTimer: number;
    oath: OathConfig;
    roleList: UserRole[];
    twoFa: TwoFaConfig;
}>();

const form = useTemplateRef("settings-form");

const require2Fa = computed(() => form?.value?.values.twoFa.required ?? false);
const allowOath = computed(() => form?.value?.values.oath.allow_login ?? false);

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
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
        allow_via_email: boolean()
            .required()
            .when(["required", "allow_via_authenticator"], {
                is: (required: boolean, app: boolean): boolean =>
                    required && !app,
                then: (schema) =>
                    schema.oneOf(
                        [true],
                        "At least one Authenticator method must be selected",
                    ),
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
        ref="settings-form"
        name="user-admin-settings-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="put"
        submit-text="Update User Settings"
        @success="$emit('success')"
    >
        <fieldset
            class="border border-slate-300 rounded-lg px-2 flex flex-col gap-2 py-2"
        >
            <legend>Two Factor Authentication</legend>
            <SwitchInput
                name="twoFa.required"
                label="Require Two-Factor Authentication"
            />
            <Collapse :show="require2Fa" class="flex flex-col gap-2">
                <SwitchInput
                    name="twoFa.allow_save_device"
                    label="Allow Users to Save Devices for Future Login"
                />
                <SwitchInput
                    name="twoFa.allow_via_email"
                    label="Allow Email as Two Factor Method"
                />
                <SwitchInput
                    name="twoFa.allow_via_authenticator"
                    label="Allow Authenticator App as Two Factor Method"
                />
            </Collapse>
        </fieldset>
        <fieldset
            class="border border-slate-300 rounded-lg px-2 flex flex-col gap-2 py-2"
        >
            <legend>Single Sign On</legend>
            <SwitchInput
                id="allow-oath"
                name="oath.allow_login"
                label="Allow Office 365 Login"
            />
            <Collapse :show="allowOath" class="flex flex-col gap-2">
                <SwitchInput
                    id="oath_register"
                    name="oath.allow_register"
                    class="w-100"
                    label="Allow anyone in my organization to login"
                />
                <SelectInput
                    id="default_role_id"
                    name="oath.default_role_id"
                    label="User Role When Creating New User"
                    :list="roleList"
                    text-field="name"
                    value-field="role_id"
                />
                <TextInput
                    id="azure-tenant-id"
                    name="oath.tenant"
                    label="Azure Tenant ID"
                />
                <TextInput
                    id="azure-client-id"
                    name="oath.client_id"
                    label="Azure Client ID"
                />
                <TextInput
                    id="azure-client-secret"
                    type="password"
                    name="oath.client_secret"
                    label="Azure Client Secret"
                />
                <DatePickerInput
                    id="azure-secret-expiration"
                    name="oath.secret_expires"
                    label="Date Client Secret Expires"
                />
                <TextInput
                    id="azure-redirect"
                    type="url"
                    name="oath.redirect"
                    label="Azure Redirect URI"
                    disabled
                />
            </Collapse>
        </fieldset>
    </VueForm>
</template>
