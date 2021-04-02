<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Modify User</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <b-table striped :items="users" :fields="table.fields">
                            <template #cell(username)="data">
                                <inertia-link :href="route('admin.user.edit', data.item.username)">{{data.item.username}}</inertia-link>
                            </template>
                            <template #cell(user_roles)="data">
                                {{data.item.user_roles.name}}
                            </template>
                            <template #cell(actions)="data">
                                <inertia-link :href="route('admin.user.edit', data.item.username)" title="Edit User" v-b-tooltip.hover>
                                    <i class="fas fa-edit text-dark"></i>
                                </inertia-link>
                                <i class="fas fa-key pointer" title="Reset Password" v-b-tooltip.hover v-b-modal.password-modal @click="userData = data.item"></i>
                                <i class="fas fa-user-slash pointer" title="Disable User" v-b-tooltip.hover @click="disableUser(data.item)"></i>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="password-modal" id="password-modal" title="Reset User Password">
            <div class="card-title">Please Enter New Password for {{userData.full_name}}</div>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <ValidationProvider v-slot="v" rules="required|confirmed:confirmation|min:6">
                            <b-form-group
                                label="Password:"
                                label-for="password"
                            >
                                <b-form-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Enter New Password"
                                    v-model="form.password"
                                ></b-form-input>
                                <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                        <ValidationProvider v-slot="v" vid="confirmation" rules="required|min:6">
                            <b-form-group
                                label="Confirm Password:"
                                label-for="password_confirmation"
                            >
                                <b-form-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Confirm New Password"
                                    v-model="form.password_confirmation"
                                ></b-form-input>
                                <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                        <submit-button button_text="Reset Password" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            users: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                table: {
                    fields: [
                        {
                            key:     'username',
                            label:   'Username',
                            sortable: true,
                        },
                        {
                            key:     'email',
                            label:   'Email',
                            sortable: true,
                        },
                        {
                            key:     'full_name',
                            label:   'Name',
                            sortable: true,
                        },
                        {
                            key:     'user_roles',
                            label:   'Role',
                            sortable: true,
                        },
                        {
                            key:     'actions',
                            label:   'Actions',
                            sortable: false,
                        },
                    ]
                },
                form: {
                    password: '',
                    password_confirmation: '',
                },
                submitted: false,
                userData: '',
            }
        },
        methods: {
            disableUser(row)
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate '+row.full_name, {
                    title:          'Please Confirm',
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
                        this.$inertia.delete(this.route('admin.user.destroy', row.username));
                    }
                });
            },
            submitForm()
            {
                this.submitted = true;
                this.$inertia.put(route('password.update', this.userData.username), this.form, {onFinish: () => {
                    this.form.password = '';
                    this.form.password_confirmation = '';
                    this.submitted = false
                    this.$refs['password-modal'].hide();
                }});
            }
        }
    }
</script>
