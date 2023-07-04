<template>
    <VueForm
        ref="resetPasswordForm"
        id="reset-password-form"
        :initial-values="initValues"
        :validation-schema="validation"
        @submit="onSubmit"
    >
        <TextInput
            id="email"
            name="email"
            placeholder="Email Address"
            type="email"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

type emailForm = {
    email: string;
};

const resetPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    email: "",
};
const validation = object({
    email: string().email().required(),
});

const onSubmit = (form: emailForm) => {
    const formData = useForm(form);
    formData.post(route("password.forgot"), {
        onFinish: () => resetPasswordForm.value?.endSubmit(),
    });
};
</script>
