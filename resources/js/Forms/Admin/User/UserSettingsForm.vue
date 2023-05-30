<template>
    <VueForm
        ref="passwordPolicyForm"
        :validation-schema="policy.validationSchema"
        :initial-values="policy.initialValues"
        submit-text="Save"
        @submit="onSubmit"
    >
        <TextInput
            id="password-expires"
            name="expire"
            label="Password Expires in Days (enter 0 for no expiration)"
        />
        <fieldset>
            <legend>Password Complexity</legend>
            <RangeInput
                id="min-length"
                name="min_length"
                label="Password Minimum Length"
                :min="3"
                :max="25"
            />
            <div class="m-3">
                <p>
                    A password should contain at least one each of the
                    following:
                </p>
                <CheckboxSwitch
                    id="policy-uppercase"
                    name="contains_uppercase"
                    label="Uppercase Letter"
                />
                <CheckboxSwitch
                    id="policy-lowercase"
                    name="contains_lowercase"
                    label="Lowercase Letter"
                />
                <CheckboxSwitch
                    id="policy-number"
                    name="contains_number"
                    label="Number (0-9)"
                />
                <CheckboxSwitch
                    id="policy-special"
                    name="contains_special"
                    label="Special Character (!@#$%^&*)"
                />
            </div>
        </fieldset>
        <fieldset>
            <legend>Office 365 Login</legend>
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <CheckboxSwitch
                        id="oath-login"
                        name="allowOath"
                        class="d-inline-block"
                        label="Allow Office 365 Login"
                        data-bs-toggle="collapse"
                        data-bs-target="#oath-form-data"
                    >
                    </CheckboxSwitch>
                </div>
            </div>
            <div class="row justify-content-center">
                <div
                    id="oath-form-data"
                    class="col-md-11 border collapse p-2 m-0"
                    :class="{ show: policy.allowOath }"
                >
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <CheckboxSwitch
                                id="oath-register"
                                name="allowRegister"
                                class="d-inline-block"
                                label="Allow anyone in my organization to login"
                                help="If left unchecked, only users created manually can log into the Tech Bench"
                            />
                        </div>
                    </div>
                    <TextInput
                        id="azure-tenant-id"
                        name="tenantId"
                        label="Azure Tenant ID"
                    />
                    <TextInput
                        id="azure-client-id"
                        name="clientId"
                        label="Azure Client ID"
                    />
                    <TextInput
                        id="azure-client-secret"
                        type="password"
                        name="clientSecret"
                        label="Azure Client Secret"
                    />
                    <TextInput
                        id="azure-redirect"
                        type="url"
                        name="redirectUri"
                        label="Azure Redirect URI"
                        disabled
                    />
                </div>
            </div>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import RangeInput from "@/Components/Base/Input/RangeInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import { ref, reactive } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string, number, boolean } from "yup";

type passwordPolicyType = {
    expire: number;
    min_length: number;
    contains_uppercase: boolean;
    contains_lowercase: boolean;
    contains_number: boolean;
    contains_special: boolean;
    allowOath: boolean;
    allowRegister: boolean;
    tenantId: string;
    clientId: string;
    clientSecret: string;
    redirectUri: string;
};

const passwordPolicyForm = ref<InstanceType<typeof VueForm> | null>(null);
const props = defineProps<{
    policy: passwordPolicyType;
}>();
const emit = defineEmits(["step-completed"]);

const policy = reactive({
    validationSchema: object({
        expire: number().required("Enter 0 for no expiration timer"),
        min_length: number().required(),
        contains_uppercase: boolean().required(),
        contains_lowercase: boolean().required(),
        contains_number: boolean().required(),
        contains_special: boolean().required(),
        allowOath: boolean().required(),
        allowRegister: boolean().required(),
        tenantId: string().when("allowOath", {
            is: true,
            then: (schema) =>
                schema.required("You must enter the Azure Tenant ID"),
            otherwise: (schema) => schema.nullable(),
        }),
        clientId: string().when("allowOath", {
            is: true,
            then: (schema) =>
                schema.required("You must enter the Azure Tenant ID"),
            otherwise: (schema) => schema.nullable(),
        }),
        clientSecret: string().when("allowOath", {
            is: true,
            then: (schema) =>
                schema.required("You must enter the Azure Tenant ID"),
            otherwise: (schema) => schema.nullable(),
        }),
        redirectUri: string().required(),
    }),
    initialValues: props.policy,
});

const onSubmit = (form: passwordPolicyType) => {
    const formData = useForm(form);
    formData.post(route("admin.users.password-policy.store"), {
        onFinish: () => {
            passwordPolicyForm.value?.endSubmit();
            emit("step-completed");
        },
    });
};
</script>
