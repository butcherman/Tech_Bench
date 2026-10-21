<script setup lang="ts">
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number, boolean } from "yup";
import { testEmail } from "@/wayfinder/routes/admin";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    testValues: EmailSettings;
}>();

const initValues = props.testValues;

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
    recipient: string().email().required(),
});
</script>

<template>
    <VueForm
        name="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="testEmail.url()"
        submit-method="put"
        submit-text="Send Test Email"
        @success="$emit('success')"
    >
        <TextInput name="recipient" label="Test Recipient" focus />
        <template #submit-button>
            <div class="flex flex-row-reverse">
                <SubmitButton
                    text="Send Test Email"
                    icon="paper-plane"
                    variant="info"
                />
            </div>
        </template>
    </VueForm>
</template>
