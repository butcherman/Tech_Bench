<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number, ref as reference } from "yup";
import { computed } from "vue";
import { submit } from "@/wayfinder/routes/init/step-4";
import { step3 } from "@/wayfinder/routes/init";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    user: User;
}>();

const submitText = computed(() =>
    props.user ? "Update User Profile" : "Create User",
);

const submitMethod = computed(() => {
    return props.user ? "put" : "post";
});

const initValues = {
    username: props.user?.username ?? "",
    first_name: props.user?.first_name ?? "",
    last_name: props.user?.last_name ?? "",
    email: props.user?.email ?? "",
    role_id: 1,
    password: "",
    password_confirmation: "",
};

const schema = object({
    username: string().required(),
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
    role_id: number().required(),
    password: string().required(),
    password_confirmation: string()
        .required("You must confirm your password")
        .oneOf([reference("password")], "Passwords must match"),
});
</script>

<template>
    <VueForm
        name="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submit.url(user.username)"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="$emit('success')"
    >
        <TextInput id="username" name="username" label="Username" focus />
        <TextInput id="first-name" name="first_name" label="First Name" />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" type="email" label="Email Address" />
        <PasswordInput id="password" name="password" label="New Password" />
        <PasswordInput
            id="password-confirmed"
            name="password_confirmation"
            label="Confirm Password"
        />
        <template #submit-button>
            <div class="flex justify-center gap-3">
                <BaseButton :href="step3.url()" class="basis-1/3" text="Back" />
                <SubmitButton class="basis-1/3" text="Next" />
            </div>
        </template>
    </VueForm>
</template>
