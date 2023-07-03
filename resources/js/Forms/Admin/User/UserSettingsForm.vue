<template>
    <VueForm
        ref="userSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update User Settings"
        @submit="onSubmit"
    >
        <CheckboxSwitch
            id="require-2fa"
            name="twoFa.required"
            label="Require Two-Factor Authentication"
            help="With this enabled, users will have to verify their identity by entering an authorization code sent to their email or mobile device"
        />
        <div id="two-fa-wrapper" class="row justify-content-center">
            <div class="col-10 border">
                <CheckboxSwitch
                    id="save-device"
                    name="twoFa.allow_save_device"
                    label="Allow Users to Save Devices for Future Login"
                    help="Allowing a user to save devices will bypass the 2FA challenge on future visits with the same device for 180 days."
                    :disabled="disable2FaFields"
                />
                <CheckboxSwitch
                    id="via-email"
                    name="twoFa.allow_via_email"
                    label="Allow Via Email"
                    disabled
                />
                <CheckboxSwitch
                    id="via-sms"
                    name="twoFa.allow_via_sms"
                    label="Allow Via SMS"
                />
                <div class="row justify-content-center">
                    <div class="col-10 border">
                        <p class="text-center">
                            Tech Bench uses
                            <a href="https://twilio.com" target="_blank">
                                Twilio
                            </a>
                            to send SMS messages. Before enabling SMS, please
                            setup a Twilio account to get the information needed
                            below.
                        </p>
                        <TextInput
                            id="twilio-sid"
                            name="twilio.sid"
                            label="Twilio SID"
                            :disabled="disableTwilioFields"
                        />
                        <TextInput
                            id="twilio-token"
                            name="twilio.token"
                            label="Twilio Token"
                            type="password"
                            :disabled="disableTwilioFields"
                        />
                        <TextInput
                            id="twilio-from"
                            name="twilio.from"
                            label="Twilio From Number"
                            :disabled="disableTwilioFields"
                        />
                    </div>
                </div>
            </div>
        </div>
        <CheckboxSwitch
            id="allow-oath"
            name="oath.allow_login"
            label="Allow Office 365 Login"
        />
        <div class="row justify-content-center">
            <div class="col-10 border">
                <CheckboxSwitch
                    id="oath_register"
                    name="oath.allow_register"
                    class="w-100"
                    label="Allow anyone in my organization to login"
                    help="If left unchecked, only users created manually can log into the Tech Bench"
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
                    help="An email notification will be sent to System Administrators 30 days before the Secret expires"
                />
                <TextInput
                    id="azure-redirect"
                    type="url"
                    name="oath.redirectUri"
                    label="Azure Redirect URI"
                    help="Set your Azure Redirect URI to this setting for proper login returns"
                    disabled
                />
            </div>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { object, string, boolean } from "yup";

const props = defineProps<{
    twoFa: twoFaConfig;
    oath: oathConfig;
    twilio: twilioConfig;
}>();

const userSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = props;
const schema = object({
    twoFa: object({
        allow_save_device: boolean().required(),
        allow_via_email: boolean().required(),
        allow_via_sms: boolean().required(),
    }),
    twilio: object({
        sid: string().when("twoFa.allow_via_sms", {
            is: true,
            then: (schema) => schema.required("Please enter the Twilio SID"),
            otherwise: (schema) => schema.nullable(),
        }),
        token: string().when("twoFa.allow_via_sms", {
            is: true,
            then: (schema) => schema.required("Please enter the Twilio SID"),
            otherwise: (schema) => schema.nullable(),
        }),
        from: string().when("twoFa.allow_via_sms", {
            is: true,
            then: (schema) => schema.required("Please enter the Twilio SID"),
            otherwise: (schema) => schema.nullable(),
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
                    "You must enter the Expiration Date for the Client Secret"
                ),
            otherwise: (schema) => schema.nullable(),
        }),
        redirectUri: string().required(),
    }),
});

const disable2FaFields = computed(
    () => !userSettingsForm.value?.getFieldValue("twoFa").required
);
const disableTwilioFields = computed(
    () => !userSettingsForm.value?.getFieldValue("twoFa").allow_via_sms
);
const disableOathFields = computed(
    () => !userSettingsForm.value?.getFieldValue("oath").allow_login
);

type userSettingsForm = {
    allow_login: boolean;
    allow_register: boolean;
    tenant: string | null;
    client_id: string | null;
    client_secret: string | null;
    redirectUri: string | null;
};

const onSubmit = (form: userSettingsForm) => {
    const formData = useForm(form);

    formData.post(route("admin.user-settings.set"), {
        onFinish: () => userSettingsForm.value?.endSubmit(),
    });
};
</script>
