<template>
    <VueForm
        ref="emailSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            init
                ? $route('init.step-3.submit')
                : $route('email-settings.update')
        "
        submit-method="put"
        submit-text="Update Email Settings"
        @success="$emit('success')"
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
        <TextInput id="host-port" type="number" name="port" label="SMTP Port" />
        <SelectInput
            id="encryption-type"
            name="encryption"
            label="Encryption Method"
            :list="encryptionTypes"
        />
        <div class="text-center">
            <CheckboxSwitch
                id="require-auth"
                name="require_auth"
                label="Require Authentication"
                inline
                data-bs-toggle="collapse"
                data-bs-target="#auth-data"
            />
        </div>
        <!-- TODO - Animate Me  -->
        <div class="row justify-content-center">
            <div
                id="auth-data"
                class="col-md-11 border collapse p-2 m-0"
                :class="{
                    show: emailSettingsForm?.getFieldValue('require_auth'),
                }"
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
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { ref } from "vue";
import { object, string, number, boolean } from "yup";

defineEmits(["success"]);
const props = defineProps<{
    settings: { [key: string]: string };
    init?: boolean;
}>();

const emailSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = props.settings;
const schema = object({
    from_address: string().email().required().label("From Address"),
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

const encryptionTypes = ["NONE", "TLS", "SSL"];
</script>
