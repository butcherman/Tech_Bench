<script setup lang="ts">
import TextInput from "@/core/forms/TextInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { object, string } from "yup";
import { update } from "@/wayfinder/routes/user/user-account";

const props = defineProps<{
    user: User;
}>();

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
};

const schema = object({
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
});
</script>

<template>
    <VueForm
        name="user-settings-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url(user.username)"
        submit-method="put"
        submit-text="Update Account"
    >
        <TextInput id="first-name" name="first_name" label="First Name" />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" label="Email Address" />
    </VueForm>
</template>
