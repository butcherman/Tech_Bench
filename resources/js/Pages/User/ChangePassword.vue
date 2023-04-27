<template>
    <Head title="Change Password" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Please Enter New Password
                        </h5>
                        <VueForm
                            ref="resetForm"
                            :validation-schema="validationSchema"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="current-password"
                                name="current_password"
                                label="Current Password"
                                type="password"
                            />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App         from '@/Layouts/app.vue';
    import TextInput   from '@/Components/Base/Input/TextInput.vue';
    import VueForm     from '@/Components/Base/VueForm.vue';
    import { ref }     from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import * as yup    from 'yup';

    const resetForm = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        current_password     : yup.string().required('Please enter your current password'),
        password             : yup.string().required('You must enter a password'),
        password_confirmation: yup.string().required('Enter password again'),
    }

    interface passwordFormType {
        current_password     : string;
        password             : string;
        password_confirmation: string;
    }

    const onSubmit = (form:passwordFormType) => {
        const formData = useForm(form);

        formData.post(route('settings.password.store'), {
            onFinish: () => resetForm.value?.endSubmit(),
        });
    };
</script>

<script lang="ts">
    export default { layout: App }
</script>
