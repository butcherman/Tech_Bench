<script setup lang="ts">
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string } from "yup";
import { update } from "@/wayfinder/routes/admin/security";

defineEmits<{
    success: [];
}>();

const initValues = {
    countryName: "US",
    stateOrProvinceName: "",
    localityName: "",
    organizationName: "",
    organizationalUnitName: "",
    commonName: "",
    emailAddress: "",
};

const schema = object({
    countryName: string().required().label("Country Name"),
    stateOrProvinceName: string().required().label("State or Province Name"),
    localityName: string().required().label("Locality Name"),
    organizationName: string().required().label("Organization Name"),
    organizationalUnitName: string()
        .required()
        .label("Organizational Unit Name"),
    commonName: string().required().label("Common Name"),
    emailAddress: string().email().required().label("Email Address"),
});
</script>

<template>
    <VueForm
        name="generate-csr-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url('generate-csr')"
        submit-method="put"
        submit-text="Generate CSR"
        @success="$emit('success')"
    >
        <TextInput name="countryName" label="Country Name" />
        <TextInput name="stateOrProvinceName" label="State or Province Name" />
        <TextInput name="localityName" label="Locality Name" />
        <TextInput name="organizationName" label="Organization Name" />
        <TextInput
            name="organizationalUnitName"
            label="Organizational Unit Name"
        />
        <TextInput name="commonName" label="Common Name" />
        <TextInput name="emailAddress" label="Email Address" type="email" />
    </VueForm>
</template>
