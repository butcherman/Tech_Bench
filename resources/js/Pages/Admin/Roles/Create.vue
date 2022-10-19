<template>
    <App>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create Role</div>
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
                            <div class="row" v-for="(group, name) in permissions" :key="name">
                                <h6>{{ name }}</h6>
                                <div
                                    class="col-6 col-lg-4"
                                    v-for="opt in group"
                                    :key="opt.perm_type_id"
                                >
                                    <CheckboxSwitch
                                        :id="`type-${opt.perm_type_id}`"
                                        :name="`type-${opt.perm_type_id}`"
                                        :label="opt.description"
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
                  userRoleType } from '@/Types';

    const roleForm = ref<InstanceType<typeof VueForm> | null>(null);
    const props    = defineProps<{
        permissions: {
            [key:string]:userRoleType[];
        }
    }>();

    const objectifyPermissions = ():RoleFormType => {
        let initValues:RoleFormType = {
            name       : '',
            description: '',
        }

        Object.values(props.permissions).forEach(perm => {
            perm.forEach(item => {
                initValues[`type-${item.perm_type_id}`] = false;
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
        formData.post(route('admin.users.roles.store'), {
            onFinish: () => roleForm.value?.endSubmit(),
        });
    }
</script>
