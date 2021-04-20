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
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.name" rules="required" label="Role Name" name="name" :errors="errors"></text-input>
                                    <text-input v-model="form.description" rules="required" label="Short Description" name="description" :errors="errors"></text-input>
                                    <b-form-group label="Role Permissions:">
                                        <div class="row">
                                            <div class="col-4" v-for="opt in form.user_role_permissions" :key="opt.perm_type_id">
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
                                    <submit-button :button_text="button_text" :submitted="submitted" class="mt-3" />
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
            errors: {
                type:     Object,
                required: false,
            },
            permissions: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: {
                    name: '',
                    description: '',
                    user_role_permissions: this.permissions,
                },
                button_text: 'Create Role',
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$inertia.post(route('admin.user-roles.store'), this.form);
            }
        }
    }
</script>
