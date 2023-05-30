<template>
    <Head title="Forgot Password" />
    <AuthLayout>
        <h6 class="text-center">
            Enter your email address for instructions on accessing your account.
        </h6>
        <VueForm
            ref="forgotForm"
            :validation-schema="validationSchema"
            submit-text="Send Email"
            @submit="onSubmit"
        >
            <TextInput
                id="email-address"
                type="email"
                label="Email Address"
                placeholder="Email Address"
                name="email"
                class="no-label"
                focus
            />
        </VueForm>
    </AuthLayout>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/authLayout.vue";
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string } from "yup";

const forgotForm = ref<InstanceType<typeof VueForm> | null>(null);
const validationSchema = object({
    email: string().email().required("You must enter an email address"),
});

type forgotForm = {
    email: string;
};

const onSubmit = (form: forgotForm) => {
    const loginForm = useForm(form);

    loginForm.post(route("password.submit-email"), {
        onFinish: () => forgotForm.value?.endSubmit(),
    });
};
</script>
