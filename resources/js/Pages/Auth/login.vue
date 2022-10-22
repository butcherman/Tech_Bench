<template>
    <Head title="Login" />
    <AuthLayout>
        <VueForm
            ref="loginForm"
            :validation-schema="validationSchema"
            @submit="onSubmit"
        >
            <TextInput
                id="username"
                label="Username"
                name="username"
                focus
            />
            <TextInput
                id="passsword"
                label="Password"
                name="password"
                type="password"
            />
            <CheckboxSwitch id="remember-me" name="remember" label="Remember Me" />
        </VueForm>
        <div class="form-group row justify-content-center mb-0">
            <div class="col-md-8 text-center">
                <Link
                    class="btn btn-link text-muted"
                    :href="route('password.forgot')"
                >
                    Forgot Your Password?
                </Link>
                <a :href="route('azure-login')">Office 365 Login</a>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup lang="ts">
    import AuthLayout  from '@/Layouts/authLayout.vue';
    import VueForm     from '@/Components/Base/VueForm.vue'
    import TextInput   from '@/Components/Base/Input/TextInput.vue';
    import CheckboxSwitch from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref }     from 'vue';
    import { useForm } from '@inertiajs/inertia-vue3';
    import * as yup    from 'yup';

    const loginForm        = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema =  {
        username: yup.string().required('You must enter a username'),
        password: yup.string().required('You must enter a password'),
    }

    interface loginFormType {
        username: string;
        password: string;
    }

    const onSubmit = (form:loginFormType) => {
        const submittedData = useForm(form);
        submittedData.post(route('login.submit'), {
            onFinish: () => loginForm.value?.endSubmit(),
        });
    }
</script>
