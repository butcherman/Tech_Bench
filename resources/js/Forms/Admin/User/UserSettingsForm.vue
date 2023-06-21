<template>
    <VueForm
        ref="userSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update User Settings"
        @submit="onSubmit"
    >
        <CheckboxSwitch
            id="allow-oath"
            name="allow_login"
            label="Allow Office 365 Login"
            @change="toggleOathDisable"
        />
        <div class="row justify-content-center">
            <div class="col-10 border">
                <CheckboxSwitch
                    id="oath_register"
                    name="allow_register"
                    class="w-100"
                    label="Allow anyone in my organization to login"
                    help="If left unchecked, only users created manually can log into the Tech Bench"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-tenant-id"
                    name="tenant"
                    label="Azure Tenant ID"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-client-id"
                    name="client_id"
                    label="Azure Client ID"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-client-secret"
                    type="password"
                    name="client_secret"
                    label="Azure Client Secret"
                    :disabled="disableOathFields"
                />
                <TextInput
                    id="azure-secret-expiration"
                    name="secret_expires"
                    label="Date Client Secret Expires"
                    type="date"
                    :disabled="disableOathFields"
                    help="An email notification will be sent to System Administrators 30 days before the Secret expires"
                />
                <TextInput
                    id="azure-redirect"
                    type="url"
                    name="redirectUri"
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
import { ref, onMounted } from "vue";
import { object, string, boolean } from "yup";

const props = defineProps<{
    allow_login: boolean;
    allow_register: boolean;
    tenant: string | null;
    client_id: string | null;
    client_secret: string | null;
    redirectUri: string | null;
}>();

const userSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = props;
const schema = object({
    allow_login: boolean().required(),
    allow_register: boolean().required(),
    tenant: string().when("allow_login", {
        is: true,
        then: (schema) => schema.required("You must enter the Azure Tenant ID"),
        otherwise: (schema) => schema.nullable(),
    }),
    client_id: string().when("allow_login", {
        is: true,
        then: (schema) => schema.required("You must enter the Azure Client ID"),
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
});

const disableOathFields = ref(
    !!!userSettingsForm.value?.getFieldValue("allow_login")
);
const toggleOathDisable = () => {
    disableOathFields.value =
        !!!userSettingsForm.value?.getFieldValue("allow_login");
};

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

onMounted(() => toggleOathDisable());
</script>
