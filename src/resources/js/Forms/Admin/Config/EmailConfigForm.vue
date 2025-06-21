<script setup lang="ts">
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, number, boolean } from "yup";
import { computed, ref } from "vue";
import { growShow, shrinkHide } from "@/Composables/animations.module";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    init?: boolean;
    settings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
}>();

const showAuth = ref<boolean>(props.settings.require_auth);
const toggleAuth = (): void => {
    showAuth.value = !showAuth.value;
};

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed<string>(() =>
    props.init
        ? route("init.step-2.submit")
        : route("admin.email-settings.update")
);

const submitText = computed<string>(() =>
    props.init ? "Save and Continue" : "Update Email Settings"
);

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
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

const encryptionTypes = [
    {
        text: "NONE",
        value: "NONE",
    },
    {
        text: "TLS",
        value: "TLS",
    },
    {
        text: "SSL",
        value: "SSL",
    },
];
</script>

<template>
    <VueForm
        submit-method="put"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
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
        <div class="flex justify-center">
            <SwitchInput
                id="require-auth"
                name="require_auth"
                label="Require Authentication"
                inline
                @change="toggleAuth"
            />
        </div>
        <div class="row justify-content-center">
            <Transition @enter="growShow" @leave="shrinkHide">
                <div
                    v-if="showAuth"
                    id="auth-data"
                    class="col-md-11 border p-2 m-0"
                    style="height: auto; opacity: 1"
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
            </Transition>
        </div>
    </VueForm>
</template>
