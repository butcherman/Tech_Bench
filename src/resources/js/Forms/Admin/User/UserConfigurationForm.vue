<script setup lang="ts">
import DatePicker from "@/Forms/_Base/DatePicker.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, boolean } from "yup";
import { ref } from "vue";
import { growShow, shrinkHide } from "@/Composables/animations.module";

const props = defineProps<{
    autoLogoutTimer: number;
    oath: oathConfig;
    roleList: userRole[];
    twoFa: twoFaConfig;
}>();

const require2Fa = ref<boolean>(props.twoFa.required);
const allowOath = ref<boolean>(props.oath.allow_login);

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

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.user.user-settings.update')"
        submit-method="put"
        submit-text="Update User Settings"
    >
        <fieldset class="border mb-3">
            <legend>Two Factor Authentication</legend>
            <div class="ms-4">
                <SwitchInput
                    id="require-2fa"
                    name="twoFa.required"
                    label="Require Two-Factor Authentication"
                    @change="require2Fa = !require2Fa"
                />
                <Transition @enter="growShow" @leave="shrinkHide">
                    <SwitchInput
                        v-show="require2Fa"
                        id="save-device"
                        name="twoFa.allow_save_device"
                        label="Allow Users to Save Devices for Future Login"
                    />
                </Transition>
            </div>
        </fieldset>
        <fieldset class="border mb-3">
            <legend>Single Sign On</legend>
            <div class="ms-4">
                <SwitchInput
                    id="allow-oath"
                    name="oath.allow_login"
                    label="Allow Office 365 Login"
                    @change="allowOath = !allowOath"
                />
                <Transition @enter="growShow" @leave="shrinkHide">
                    <div v-show="allowOath">
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
                        <SwitchInput
                            id="two_fa_bypass"
                            name="oath.allow_bypass_2fa"
                            class="w-100"
                            label="Allow Single Sign On Users to Bypass Two-Factor Authentication"
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
                        <!-- <TextInput
                            id="azure-secret-expiration"
                            name="oath.secret_expires"
                            label="Date Client Secret Expires"
                            type="date"
                        /> -->
                        <DatePicker
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
                    </div>
                </Transition>
            </div>
        </fieldset>
    </VueForm>
</template>
