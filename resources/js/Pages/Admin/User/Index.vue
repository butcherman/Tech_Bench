<template>
    <Head title="User List" />
    <App>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.user_id">
                                    <td>{{ user.username }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.full_name }}</td>
                                    <td>{{ user.user_roles.name }}</td>
                                    <td>
                                        <Link
                                            :href="route('admin.user.edit', user.username)"
                                            title="Edit User"
                                            v-tooltip
                                        >
                                            <fa-icon
                                                class="mx-1 pointer text-primary"
                                                icon="edit"
                                            />
                                        </Link>
                                        <!-- <fa-icon class="mx-1 pointer text-primary" icon="key" title="Reset Password" v-tooltip /> -->
                                        <!-- <fa-icon class="mx-1 pointer text-danger" icon="user-slash" title="Disable User" v-tooltip /> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- <b-modal ref="password-modal" id="password-modal" title="Reset User Password" hide-footer>
            <div class="card-title">Please Enter New Password for {{form.full_name}}</div>
                <ValidationObserver v-slot="{handleSubmit}" ref="validator">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input v-model="form.password" type="password" rules="required|confirmed:confirmation|min:6" label="New Password" name="password" placeholder="Enter New Password"></text-input>
                        <text-input v-model="form.password_confirmation" type="password" rules="required" vid="confirmation" label="Confirm Password" name="password_confirmation" placeholder="Confirm New Password"></text-input>
                        <submit-button button_text="Reset Password" :submitted="submitted"></submit-button>
                    </b-form>
                </ValidationObserver>
        </b-modal> -->
    </App>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import type { userType } from '@/Types';

    interface userWithRole extends userType {
        user_id   : number;
        user_roles: {
            description: string;
            name       : string;
            role_id    : number;
        }
    }

    const props = defineProps<{
        users: userWithRole[];
    }>();
    // import App from '../../../Layouts/app';

    // export default {
    //     layout: App,
    //     props: {
    //         /**
    //          * Array of objects from /app/Models/User
    //          */
    //         users: {
    //             type:     Array,
    //             required: true,
    //         }
    //     },
    //     data() {
    //         return {
    //             table: {
    //                 fields: [
    //                     {
    //                         key:     'username',
    //                         label:   'Username',
    //                         sortable: true,
    //                     },
    //                     {
    //                         key:     'email',
    //                         label:   'Email',
    //                         sortable: true,
    //                     },
    //                     {
    //                         key:     'full_name',
    //                         label:   'Name',
    //                         sortable: true,
    //                     },
    //                     {
    //                         key:     'user_roles',
    //                         label:   'Role',
    //                         sortable: true,
    //                     },
    //                     {
    //                         key:     'actions',
    //                         label:   'Actions',
    //                         sortable: false,
    //                     },
    //                 ]
    //             },
    //             submitted: false,
    //             form:      this.$inertia.form({
    //                 username:              null,
    //                 full_name:             null,
    //                 password:              null,
    //                 password_confirmation: null,
    //             }),
    //         }
    //     },
    //     methods: {
    //         /**
    //          * Open the reset password Modal
    //          */
    //         resetPassword(user)
    //         {
    //             this.form.full_name = user.full_name;
    //             this.form.username  = user.username;
    //             this.$refs['password-modal'].show();
    //         },
    //         /**
    //          * Soft delete the user
    //          */
    //         disableUser(user)
    //         {
    //             this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate '+user.full_name, {
    //                 title:          'Please Confirm',
    //                 size:           'sm',
    //                 buttonSize:     'sm',
    //                 okVariant:      'danger',
    //                 okTitle:        'YES',
    //                 cancelTitle:    'NO',
    //                 footerClass:    'p-2',
    //                 hideHeaderClose: false,
    //                 centered:        true
    //             }).then(value => {
    //                 if(value)
    //                 {
    //                     this.$inertia.delete(this.route('admin.user.destroy', user.username));
    //                 }
    //             });
    //         },
    //         submitForm()
    //         {
    //             this.submitted = true;
    //             this.form.put(route('password.update', this.form.username), {
    //                 onSuccess: () => {
    //                     this.form.reset();
    //                     this.$refs['password-modal'].hide();
    //                     this.$refs['validator'].reset();
    //                     this.submitted = false;
    //                 }
    //             });
    //         },
    //     },
    //     metaInfo: {
    //         title: 'Users',
    //     }
    // }
</script>
