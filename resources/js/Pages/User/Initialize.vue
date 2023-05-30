<template>
    <Head title="Setup Account" />
    <AuthLayout>
        <h5 class="text-center text-dark">Welcome {{ user.full_name }}</h5>
        <h6 class="text-center">
            Create a password to finish setting up your account
        </h6>
        <VueForm
            ref="setPasswordForm"
            :validation-schema="validationSchema"
            submit-text="Save Password"
            @submit="onSubmit"
        >
            <TextInput
                id="password"
                name="password"
                label="New Password"
                placeholder="New Password"
                class="no-label"
                type="password"
            />
            <TextInput
                id="password-confirmation"
                name="password_confirmation"
                label="Confirm Password"
                placeholder="Confirm Password"
                class="no-label"
                type="password"
            />
        </VueForm>
        <div class="row justify-content-center">
            <div class="col grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Password Rules:</h5>
                        <ul>
                            <li v-for="rule in password_rules" class="mx-2">
                                {{ rule }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/authLayout.vue";
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import * as yup from "yup";

const props = defineProps<{
    link: {
        username: string;
        token: string;
    };
    user: user;
    password_rules: string[];
}>();

const setPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);
const validationSchema = {
    password: yup.string().required("You must enter a password"),
    password_confirmation: yup.string().required("Enter password again"),
};

type passwordFormType = {
    password: string;
    password_confirmation: string;
};

const onSubmit = (form: passwordFormType) => {
    const formData = useForm(form);

    formData.put(route("finish-setup", props.link.token), {
        onFinish: () => setPasswordForm.value?.endSubmit(),
    });
};
</script>
