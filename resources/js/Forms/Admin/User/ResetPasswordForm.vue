<template>
    <VueForm
        ref="resetPasswordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Reset Password"
        @submit="onSubmit"
    >
        <TextInput
            id="password"
            name="password"
            label="Password"
            type="password"
        />
        <TextInput
            id="password-confirmation"
            name="password_confirmation"
            label="Confirm Password"
            type="password"
        />
        <CheckboxSwitch
            id="change-required"
            class="mb-2"
            name="changeRequired"
            label="Force change on next login"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { boolean, object, string } from "yup";

const emit = defineEmits(["completed"]);
const props = defineProps<{
    user: user;
}>();

const resetPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    changeRequired: true,
    password: "",
    password_confirmation: "",
};
const schema = object({
    password: string().required(),
    password_confirmation: string()
        .required("Enter Password Again")
        .test("confirmed", "Passwords do not match", (value) => {
            return value === resetPasswordForm.value?.getFieldValue("password");
        }),
    changeRequired: boolean().required(),
});

type passwordForm = {
    changeRequired: boolean;
    password: string;
    password_confirmation: string;
};

const onSubmit = (form: passwordForm) => {
    const formData = useForm(form);
    console.log(formData);
    formData.post(route("admin.users.reset-password", props.user.username), {
        onFinish: () => resetPasswordForm.value?.endSubmit(),
        onSuccess: () => emit("completed"),
    });
};
</script>
