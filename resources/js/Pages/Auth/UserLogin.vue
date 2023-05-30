<template>
    <Head title="Login" />
    <AuthLayout>
        <VueForm
            ref="loginForm"
            id="loginForm"
            :class="{ 'apply-shake': shake }"
            :validation-schema="validationSchema"
            submit-text="Login"
            @submit="onSubmit"
        >
            <TextInput
                id="username"
                label="Username"
                name="username"
                placeholder="Username"
                class="no-label"
                focus
            />
            <TextInput
                id="password"
                label="Password"
                name="password"
                placeholder="Password"
                class="no-label"
                type="password"
            />
            <CheckboxSwitch
                id="remember-me"
                name="remember"
                label="Remember Me"
            />
        </VueForm>
        <div class="form-group row justify-content-center mb-0">
            <div v-if="allow_oath" class="col-md-8 text-center">
                <a
                    class="btn btn-link text-muted"
                    :href="$route('azure-login')"
                >
                    Login with Office 365
                </a>
            </div>
            <div class="col-md-8 text-center">
                <Link
                    class="btn btn-link text-muted"
                    :href="$route('password.forgot')"
                >
                    Forgot Your Password?
                </Link>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/authLayout.vue";
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string } from "yup";

defineProps<{
    allow_oath: boolean;
}>();
const $route = route;
const loginForm = ref<InstanceType<typeof VueForm> | null>(null);
const validationSchema = object({
    username: string().required("You must enter a username"),
    password: string().required("You must enter a password"),
});

const shake = ref(false);
onMounted(() =>
    document
        .getElementById("loginForm")
        ?.addEventListener("animationend", () => (shake.value = false))
);

type loginFormType = {
    username: string;
    password: string;
};

const onSubmit = (form: loginFormType) => {
    const submittedData = useForm(form);
    submittedData.post(route("login.submit"), {
        onFinish: () => loginForm.value?.endSubmit(),
        onError: () => (shake.value = true),
    });
};
</script>

<style scoped>
@keyframes shake {
    10%,
    90% {
        transform: translate3d(-1px, 0, 0);
    }

    20%,
    80% {
        transform: translate3d(2px, 0, 0);
    }

    30%,
    50%,
    70% {
        transform: translate3d(-4px, 0, 0);
    }

    40%,
    60% {
        transform: translate3d(4px, 0, 0);
    }
}

.apply-shake {
    animation: shake 0.82s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}
</style>
