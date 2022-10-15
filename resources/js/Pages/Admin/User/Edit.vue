<template>
    <Head title="Edit User" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Edit User Information</div>
                        <VueForm
                            ref="userForm"
                            :validation-schema="formData.validationSchema"
                            :initial-values="formData.initialValues"
                            @submit="onSubmit"
                        >
                            <TextInput id="username" name="username" label="Username" />
                            <TextInput id="first-name" name="first_name" label="First Name" />
                            <TextInput id="last-name" name="last_name" label="Last Name" />
                            <TextInput id="email" name="email" type="email" label="Email Address" />
                            <SelectInput id="role" name="role_id" label="Role" :optionList="roleTypes"/>
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App                         from '@/Layouts/app.vue';
    import VueForm                     from '@/Components/Base/VueForm.vue';
    import TextInput                   from '@/Components/Base/Input/TextInput.vue';
    import SelectInput                 from '@/Components/Base/Input/SelectInput.vue';
    import { ref, reactive, computed } from 'vue';
    import { useForm }                 from '@inertiajs/inertia-vue3';
    import * as yup                    from 'yup';
    import type { userAuthType,
                  userRoleType,
                  optionListObject,
                  userFormType }       from '@/Types';

    const props = defineProps<{
        user : userAuthType;
        roles: userRoleType[];
    }>();

    const userForm  = ref<InstanceType<typeof VueForm> | null>(null);
    const roleTypes = computed<optionListObject[]>(() => {
        const roleArr = <optionListObject[]>[];
        props.roles.forEach(role => {
            roleArr.push({
                text : role.name,
                value: role.role_id,
            });
        });

        return roleArr;
    });

    const formData = reactive({
        initialValues: {
            username  : props.user.username,
            first_name: props.user.first_name,
            last_name : props.user.last_name,
            email     : props.user.email,
            role_id   : props.user.role_id,
        },
        validationSchema: {
            username  : yup.string().required('You must enter a Username'),
            first_name: yup.string().required('First Name is required'),
            last_name : yup.string().required('Last Name is required'),
            email     : yup.string().email().required('Email Address is required'),
            role_id   : yup.number().required('You must select a Role'),
        },
    });

    const onSubmit = (form:userFormType) => {
        const formData = useForm(form);
        formData.put(route('admin.user.update', props.user.user_id), {
            onFinish: () => userForm.value?.endSubmit(),
        });
    }
</script>
