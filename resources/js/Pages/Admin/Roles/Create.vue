<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">New User Role</h4>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Creating Role..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.name" rules="required" label="Role Name" name="name"></text-input>
                                    <text-input v-model="form.description" rules="required" label="Short Description" name="description"></text-input>
                                    <b-form-group label="Role Permissions:">
                                        <div class="row" v-for="(group, name) in form.user_role_permissions" :key="name">
                                            <h4 class="w-100">{{name.length ? name : 'Misc'}}</h4>
                                            <div class="col-6 col-lg-4" v-for="opt in group" :key="opt.perm_type_id">
                                                <b-form-checkbox
                                                    v-model="opt.allow"
                                                    value="1"
                                                    unchecked-value="0"
                                                    switch
                                                >
                                                    {{opt.description}}
                                                </b-form-checkbox>
                                            </div>
                                        </div>
                                    </b-form-group>
                                    <submit-button button_text="Create Role" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
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
                    name: '',
                    description: '',
                    user_role_permissions: this.permissions,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.post(route('admin.user-roles.store'));
            }
        },
        metaInfo: {
            title: 'Create New Role',
        }
    }
</script>
