<template>
    <Head title="Edit Role" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Edit Role</div>
                        <VueForm
                            ref="roleForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="role-name"
                                name="name"
                                label="Role Name"
                            />
                            <TextInput
                                id="role-desc"
                                name="description"
                                label="Enter A Short Description"
                            />
                            <div
                                v-for="(group, name) in permissions"
                                :key="name"
                                class="row"
                            >
                                <h6>{{ name }}</h6>
                                <div
                                    v-for="opt in group"
                                    :key="opt.perm_type_id"
                                    class="col-6 col-lg-4"
                                >
                                    <CheckboxSwitch
                                        :id="`type-${opt.perm_type_id}`"
                                        :name="`type-${opt.perm_type_id}`"
                                        :label="opt.user_role_permission_types.description"
                                    />
                                </div>
                            </div>
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App                   from '@/Layouts/app.vue';
    import VueForm               from '@/Components/Base/VueForm.vue';
    import TextInput             from '@/Components/Base/Input/TextInput.vue';
    import CheckboxSwitch        from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref }               from 'vue';
    import { useForm }           from '@inertiajs/inertia-vue3';
    import * as yup              from 'yup';
    import type { RoleFormType,
                  userRolePermissionsType,
                  userRoleType } from '@/Types';

    const roleForm = ref<InstanceType<typeof VueForm> | null>(null);
    const props    = defineProps<{
        role       : userRoleType;
        permissions: {
            [key:string]:userRolePermissionsType[];
        }
    }>();

    const objectifyPermissions = ():RoleFormType => {
        let initValues:RoleFormType = {
            name       : props.role.name,
            description: props.role.description,
        }

        Object.values(props.permissions).forEach(perm => {
            perm.forEach(item => {
                initValues[`type-${item.perm_type_id}`] = item.allow;
            });
        });

        return initValues;
    }

    const initialValues    = objectifyPermissions();
    const validationSchema = {
        name       : yup.string().required('The Role must have a name'),
        description: yup.string().required('Please provide a short description of the Role'),
    }

    const onSubmit = (form:RoleFormType) => {
        const formData = useForm(form);
        formData.put(route('admin.users.roles.update', props.role.role_id), {
            onFinish: () => roleForm.value?.endSubmit(),
        });
    }
</script>