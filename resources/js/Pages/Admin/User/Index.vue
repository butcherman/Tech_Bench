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
                        <b-table responsive striped :items="users" :fields="table.fields">
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
                                <i class="fas fa-key pointer" title="Reset Password" v-b-tooltip.hover @click="resetPassword(data.item)"></i>
                                <i class="fas fa-user-slash pointer" title="Disable User" v-b-tooltip.hover @click="disableUser(data.item)"></i>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="password-modal" id="password-modal" title="Reset User Password" hide-footer>
            <div class="card-title">Please Enter New Password for {{form.full_name}}</div>
                <ValidationObserver v-slot="{handleSubmit}" ref="validator">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input v-model="form.password" type="password" rules="required|confirmed:confirmation|min:6" label="New Password" name="password" placeholder="Enter New Password"></text-input>
                        <text-input v-model="form.password_confirmation" type="password" rules="required" vid="confirmation" label="Confirm Password" name="password_confirmation" placeholder="Confirm New Password"></text-input>
                        <submit-button button_text="Reset Password" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
        </b-modal>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

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
                submitted: false,
                form:      this.$inertia.form({
                    username:              null,
                    full_name:             null,
                    password:              null,
                    password_confirmation: null,
                }),
            }
        },
        methods: {
            resetPassword(user)
            {
                this.form.full_name = user.full_name;
                this.form.username  = user.username;
                this.$refs['password-modal'].show();
            },
            disableUser(user)
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate '+user.full_name, {
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
                        this.$inertia.delete(this.route('admin.user.destroy', user.username));
                    }
                });
            },
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('password.update', this.form.username), {
                    onSuccess: () => {
                        this.form.reset();
                        this.$refs['password-modal'].hide();
                        this.$refs['validator'].reset();
                        this.submitted = false;
                    }
                });
            },
        },
        metaInfo: {
            title: 'Users',
        }
    }
</script>
