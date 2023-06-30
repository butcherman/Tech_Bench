<template>
    <VueForm
        ref="csrForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submitText="Generate CSR"
        @submit="onSubmit"
    >
        <TextInput id="country" name="country" label="Country Name" />
        <TextInput id="state" name="state" label="State or Province Name" />
        <TextInput id="locality" name="locality" label="Locality Name" />
        <TextInput
            id="organization"
            name="organization"
            label="Organization Name"
        />
        <TextInput id="ouName" name="ouName" label="Organizational Unit Name" />
        <TextInput id="common" name="common" label="Common Name" />
        <TextInput id="email" name="email" label="Email Address" type="email" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { object, string } from "yup";

const csrForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    country: "",
    state: "",
    locality: "",
    organization: "",
    ouName: "",
    common: "",
    email: "",
};
const schema = object({
    country: string().required(),
    state: string().required(),
    locality: string().required(),
    organization: string().required(),
    ouName: string().required(),
    common: string().required(),
    email: string().email().required(),
});

type csrForm = {
    country: string;
    state: string;
    locality: string;
    organization: string;
    ouName: string;
    common: string;
    email: string;
};

const onSubmit = (form: csrForm) => {
    const formData = useForm(form);

    formData.put(route('admin.security.update'), {
        onFinish: () => csrForm.value?.endSubmit(),
    });
};
</script>
