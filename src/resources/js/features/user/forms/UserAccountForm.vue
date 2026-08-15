<script setup lang="ts">
import VueForm from "@/core/forms/components/VueForm.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import { object, string } from "yup";
import { update } from "@/wayfinder/routes/user/user-account";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    user: User;
}>();

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
        submit-method="put"
        submit-text="Update Account"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url(user.username)"
        @success="$emit('success')"
    >
        <TextInput name="first_name" label="First Name" />
        <TextInput name="last_name" label="Last Name" />
        <TextInput name="email" label="Email Address" />
    </VueForm>
</template>
