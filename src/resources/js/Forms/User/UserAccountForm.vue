<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            $route('user.user-settings.update', currentUser.username)
        "
        submit-method="put"
        submit-text="Update Account Settings"
    >
        <TextInput id="first-name" name="first_name" label="First Name" focus />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" label="Email Address" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";

const props = defineProps<{
    currentUser: user;
}>();
const initValues = {
    first_name: props.currentUser.first_name,
    last_name: props.currentUser.last_name,
    email: props.currentUser.email,
};
const schema = object({
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
});
</script>
