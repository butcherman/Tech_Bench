<template>
    <p class="text-center">
        Copy and paste SSL Certificate Data into boxes below
    </p>
    <VueForm
        ref="sslForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Upload Certificate"
        @submit="onSubmit"
    >
        <CheckboxSwitch
            id="isWildcard"
            name="wildcard"
            label="Upload Wildcard Certificate"
            data-bs-toggle="collapse"
            data-bs-target="#needs-key"
        />
        <TextAreaInput
            id="certificate"
            name="certificate"
            label="Certificate"
            :rows="9"
        />
        <div id="needs-key" class="collapse">
            <TextAreaInput id="key" name="key" label="Private Key" :rows="9" />
        </div>
        <TextAreaInput
            id="intermediate"
            name="intermediate"
            label="Intermediate"
            :rows="9"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { object, string, boolean } from "yup";

const sslForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    wildcard: false,
    certificate: "",
    key: "",
    intermediate: "",
};
const schema = object({
    wildcard: boolean().required(),
    certificate: string().required(),
    key: string().when("wildcard", {
        is: true,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    intermediate: string().required(),
});

type SecurityForm = {
    wildcard: boolean;
    certificate: string;
    key: string;
    intermediate: string;
};

const onSubmit = (form: SecurityForm) => {
    const formData = useForm(form);

    formData.post(route("admin.security.store"), {
        onFinish: () => sslForm.value?.endSubmit(),
    });
};
</script>
