<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Update User</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Edit User Information</div>
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <form-loader text="Updating User..."></form-loader>
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}" ref="validator">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.username" rules="required" label="Username" name="username"></text-input>
                                    <text-input v-model="form.first_name" rules="required" label="First Name" name="first_name"></text-input>
                                    <text-input v-model="form.last_name" rules="required" label="Last Name" name="last_name"></text-input>
                                    <text-input v-model="form.email" rules="required|email" label="Email Address" name="email"></text-input>
                                    <b-form-select v-model="form.role_id" :options="roles" text-field="name" value-field="role_id"></b-form-select>
                                    <submit-button button_text="Update User" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </ValidationObserver>
                        </b-overlay>
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
            user: {
                type:     Object,
                required: true,
            },
            roles: {
                type:     Array,
                required: true,
            },
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    username:   this.user.username,
                    first_name: this.user.first_name,
                    last_name:  this.user.last_name,
                    email:      this.user.email,
                    role_id:    this.user.role_id,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('admin.user.update', this.user.user_id));
            }
        },
        metaInfo: {
            title: 'Update User',
        }
    }
</script>
