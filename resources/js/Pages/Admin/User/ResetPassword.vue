<template>
    <Head title="Change Password" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Please Enter New Password for {{ user.full_name }}
                        </h5>
                        <VueForm
                            ref="passwordForm"
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App               from '@/Layouts/app.vue';
    import VueForm           from '@/Components/Base/VueForm.vue';
    import TextInput         from '@/Components/Base/Input/TextInput.vue';
    import { ref }           from 'vue';
    import { useForm }       from '@inertiajs/vue3';
    import * as yup          from 'yup';
    import type { userType } from '@/Types';

    const props = defineProps<{
        user: userType;
    }>();

    const passwordForm     = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        password             : yup.string().required('You must enter a password'),
        password_confirmation: yup.string().required('Enter password again'),
    }

    type resetFormType = {
        password             : string;
        password_confirmation: string;
    }

    const onSubmit = (form:resetFormType) => {
        const formData    = useForm(form);

        formData.put(route('admin.users.reset-password.update', props.user.username), {
            onFinish: () => passwordForm.value?.endSubmit(),
        });
    };
</script>

<script lang="ts">
    export default { layout: App }
</script>
