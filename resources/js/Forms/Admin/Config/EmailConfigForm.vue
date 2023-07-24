<template>
    <Overlay :loading="sendingTest">
        <VueForm
            ref="emailSettingsForm"
            :initial-values="initValues"
            :validation-schema="schema"
            submit-text="Update Email Settings"
            @submit="onSubmit"
        >
            <TextInput
                id="from-address"
                type="email"
                name="from_address"
                label="From Email Address"
                placeholder="no-reply@your-domain.com"
                help="The From Email Address that will show when an email is sent"
            />
            <TextInput
                id="host-address"
                name="host"
                label="SMTP Host"
                placeholder="smtp.your-email-server.com"
            />
            <TextInput
                id="host-port"
                type="number"
                name="port"
                label="SMTP Port"
            />
            <SelectInput
                id="encryption-type"
                name="encryption"
                label="Encryption Method"
                :list="encryptionTypes"
            />
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <CheckboxSwitch
                        id="require-auth"
                        name="require_auth"
                        label="Require Authentication"
                        data-bs-toggle="collapse"
                        data-bs-target="#auth-data"
                    >
                    </CheckboxSwitch>
                </div>
            </div>
            <div class="row justify-content-center">
                <div
                    id="auth-data"
                    class="col-md-11 border collapse p-2 m-0"
                    :class="{ show: emailSettingsForm?.getFieldValue('require_auth') }"
                >
                    <TextInput
                        id="auth-username"
                        name="username"
                        label="Username"
                        placeholder="Username"
                    />
                    <TextInput
                        id="auth-password"
                        type="password"
                        name="password"
                        label="Password"
                        placeholder="Password"
                    />
                </div>
            </div>
        </VueForm>
        <div class="text-center">
            <Link
                as="button"
                :href="$route('admin.email.test')"
                type="button"
                class="btn btn-warning mt-2 w-100"
                @click="sendingTest = true"
                @finish="sendingTest = false"
            >
                <span
                    v-if="sendingTest"
                    class="spinner-border spinner-border-sm"
                />
                Send Test Email
            </Link>
            <p>
                <strong>Note:</strong>
                You must save the email settings before sending a test email
            </p>
        </div>
    </Overlay>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { object, string, number, boolean } from "yup";

const props = defineProps<{
    settings: emailSettings;
}>();

const emailSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = props.settings;
const schema = object({
    from_address: string().email().required(),
    host: string().required(),
    port: number().required(),
    encryption: string().required(),
    require_auth: boolean().required(),
    username: string().when("require_auth", {
        is: true,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    password: string().when("require_auth", {
        is: true,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
});

const sendingTest = ref(false);
const encryptionTypes = ["NONE", "TLS", "SSL"];

const emit = defineEmits(['success']);

const onSubmit = (form: emailSettings) => {
    const formData = useForm(form);

    formData.post(route("admin.email.set"), {
        preserveScroll: true,
        onFinish: () => emailSettingsForm.value?.endSubmit(),
        onSuccess: () => emit('success'),
    });
};
</script>
