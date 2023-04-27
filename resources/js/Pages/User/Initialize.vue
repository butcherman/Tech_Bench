<template>
    <AuthLayout>
        <h5 class="text-center text-dark">Welcome {{ user.full_name }}</h5>
        <h6 class="text-center">
            Create a password to finish setting up your account
        </h6>
        <VueForm
            ref="setPasswordForm"
            :validation-schema="validationSchema"
            @submit="onSubmit"
        >
            <TextInput
                id="password"
                name="password"
                label="New Password"
                type="password"
            />
            <TextInput
                id="password-confirmation"
                name="password_confirmation"
                label="Confirm Password"
                type="password"
            />
        </VueForm>
    </AuthLayout>
</template>

<script setup lang="ts">
    import AuthLayout        from '@/Layouts/authLayout.vue';
    import VueForm           from '@/Components/Base/VueForm.vue';
    import TextInput         from '@/Components/Base/Input/TextInput.vue';
    import { ref }           from 'vue';
    import { useForm }       from '@inertiajs/vue3';
    import * as yup          from 'yup';
    import type { userType } from '@/Types';

    const props = defineProps<{
        link: {
            username: string;
            token   : string;
        };
        user: userType;
    }>();

    const setPasswordForm  = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        password             : yup.string().required('You must enter a password'),
        password_confirmation: yup.string().required('Enter password again'),
    }

    interface passwordFormType {
        password             : string;
        password_confirmation: string;
    }

    const onSubmit = (form:passwordFormType) => {
        const formData = useForm(form);

        formData.put(route('finish-setup', props.link.token), {
            onFinish: () => setPasswordForm.value?.endSubmit(),
        });
    };
</script>
