<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Edit User Role</h4>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 v-if="!role_data.allow_edit" class="text-center text-danger">You Cannot Edit A Default Role</h4>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Updating Role..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.name" rules="required" label="Role Name" name="name" :disabled="!role_data.allow_edit"></text-input>
                                    <text-input v-model="form.description" rules="required" label="Short Description" name="description" :disabled="!role_data.allow_edit"></text-input>
                                    <b-form-group label="Role Permissions:">
                                        <div class="row" v-for="(group, name) in form.user_role_permissions" :key="name">
                                            <h4 class="w-100">{{name.length ? name : 'Misc'}}</h4>
                                            <div class="col-4" v-for="opt in group" :key="opt.perm_type_id">
                                                <b-form-checkbox
                                                    v-model="opt.allow"
                                                    value="1"
                                                    unchecked-value="0"
                                                    switch
                                                    :disabled="!role_data.allow_edit"
                                                >
                                                    {{opt.user_role_permission_types.description}}
                                                </b-form-checkbox>
                                            </div>
                                        </div>
                                    </b-form-group>
                                    <submit-button v-if="role_data.allow_edit" button_text="Update Role" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="role_data.allow_edit" class="row justify-content-center grid-margins">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <b-button variant="danger" block @click="deleteRole">Delete User Role</b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Object from /app/Models/UserRoles
             */
            role_data: {
                type:     Object,
                required: true,
            },
            /**
             * Array of objects from /app/Models/UserRolePermissionType
             * Grouped by Role Category - identified as group
             */
            permissions: {
                type:     Object,
                required: true,
            },
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    name:                  this.role_data.name,
                    description:           this.role_data.description,
                    user_role_permissions: this.permissions,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('admin.user-roles.update', this.role_data.role_id));
            },
            deleteRole()
            {
                this.$bvModal.msgBoxConfirm('Are you sure you want to delete this User Role?', {
                    title:          'This action canot be undone',
                    size:           'sm',
                    buttonSize:     'sm',
                    okVariant:      'danger',
                    okTitle:        'YES',
                    cancelTitle:    'NO',
                    footerClass:    'p-2',
                    hideHeaderClose: false,
                    centered:        true
                }).then(value => {
                    if(value)
                    {
                        this.submitted = true;
                        this.$inertia.delete(this.route('admin.user-roles.destroy', this.role_data.role_id), {
                            onFinish: () => {
                                this.submitted = false;
                            }
                        });
                    }
                });
            }
        },
        metaInfo: {
            title: 'Edit Role',
        }
    }
</script>
