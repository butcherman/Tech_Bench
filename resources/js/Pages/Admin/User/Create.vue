<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Enter New User Information</div>
                        <VueForm
                            ref="userForm"
                            :validation-schema="validationSchema"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="username"
                                name="username"
                                label="Username"
                            />
                            <TextInput
                                id="first-name"
                                name="first_name"
                                label="First Name"
                            />
                            <TextInput
                                id="last-name"
                                name="last_name"
                                label="Last Name"
                            />
                            <TextInput
                                id="email"
                                name="email"
                                type="email"
                                label="Email Address"
                            />
                            <SelectInput
                                id="role"
                                name="role_id"
                                label="Role"
                                :optionList="roleTypes"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                       from '@/Layouts/app.vue';
    import VueForm                   from '@/Components/Base/VueForm.vue';
    import TextInput                 from '@/Components/Base/Input/TextInput.vue';
    import SelectInput               from '@/Components/Base/Input/SelectInput.vue';
    import { ref, computed }         from 'vue';
    import { useForm }               from '@inertiajs/vue3';
    import * as yup                  from 'yup';
    import type { userRoleType,
                  userFormType,
                  optionListObject } from '@/Types';

    const props = defineProps<{
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

    const validationSchema = {
        username  : yup.string().required('You must enter a Username'),
        first_name: yup.string().required('First Name is required'),
        last_name : yup.string().required('Last Name is required'),
        email     : yup.string().email().required('Email Address is required'),
        role_id   : yup.number().required('You must select a Role'),
    }

    const onSubmit = (form:userFormType) => {
        const formData = useForm(form);
        formData.post(route('admin.users.store'), {
            onFinish: () => userForm.value?.endSubmit(),
        })
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
