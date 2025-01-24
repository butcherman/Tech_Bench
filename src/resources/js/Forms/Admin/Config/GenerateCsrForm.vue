<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.security.update', 'generate-csr')"
        submit-method="put"
        submit-text="Generate CSR"
    >
        <TextInput id="country" name="countryName" label="Country Name" />
        <TextInput
            id="state"
            name="stateOrProvinceName"
            label="State or Province Name"
        />
        <TextInput id="locality" name="localityName" label="Locality Name" />
        <TextInput
            id="organization"
            name="organizationName"
            label="Organization Name"
        />
        <TextInput
            id="ouName"
            name="organizationalUnitName"
            label="Organizational Unit Name"
        />
        <TextInput id="common" name="commonName" label="Common Name" />
        <TextInput
            id="email"
            name="emailAddress"
            label="Email Address"
            type="email"
        />
    </VueForm>
</template>

<script setup lang="ts">
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string } from "yup";

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
