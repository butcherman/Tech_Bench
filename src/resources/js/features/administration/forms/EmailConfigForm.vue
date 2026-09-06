<script setup lang="ts">
import Collapse from "@/core/components/Collapse.vue";
import SelectInput from "@/core/forms/components/validatedInputs/SelectInput.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number, boolean } from "yup";
import { computed } from "vue";
import { submit } from "@/wayfinder/routes/init/step-2";
import { update } from "@/wayfinder/routes/admin/email-settings";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    settings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
    init?: boolean;
}>();

const encryptionTypes = ["NONE", "TLS", "SSL"];

const submitRoute = computed<string>(() =>
    props.init ? submit.url() : update.url(),
);

const submitText = computed<string>(() =>
    props.init ? "Save and Continue" : "Update Email Settings",
);

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
</script>

<template>
    <VueForm
        name="email-settings-form"
        submit-method="put"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
        v-slot="{ values }"
        do-not-reset
        @success="$emit('success')"
    >
        <TextInput
            type="email"
            name="from_address"
            label="From Email Address"
            placeholder="no-reply@your-domain.com"
            help="The From Email Address that will show when an email is sent"
        />
        <TextInput
            name="host"
            label="SMTP Host"
            placeholder="smtp.your-email-server.com"
        />
        <TextInput type="number" name="port" label="SMTP Port" />
        <SelectInput
            name="encryption"
            label="Encryption Method"
            :list="encryptionTypes"
        />
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    name="require_auth"
                    label="Require Authentication"
                />
            </div>
        </div>
        <Collapse :show="values.require_auth">
            <div class="flex flex-col gap-2">
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
        </Collapse>
    </VueForm>
</template>
