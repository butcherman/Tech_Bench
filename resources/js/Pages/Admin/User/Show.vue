<template>
    <Head title="User Details" />
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">User Details</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="text-end">Username:</th>
                                <td>{{ user.username }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">First Name:</th>
                                <td>{{ user.first_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Last Name:</th>
                                <td>{{ user.last_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Email Address:</th>
                                <td>{{ user.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Role:</th>
                                <td>
                                    <span :title="role.description" v-tooltip>
                                        {{ role.name }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end">Last Login:</th>
                                <td>
                                    <span v-if="lastLogin">
                                        {{ lastLogin.created_at }}
                                    </span>
                                    <span v-else class="text-danger">
                                        User has never logged in
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Actions</div>
                    <button
                        v-if="!lastLogin"
                        class="btn btn-primary w-100 m-1"
                        @click="resendInvite"
                    >
                        <fa-icon icon="envelope" class="float-start mt-1" />
                        Resend Invite Email
                    </button>
                    <button class="btn btn-warning w-100 m-1">
                        <fa-icon icon="key" class="float-start mt-1" />
                        Reset Password
                    </button>
                    <button class="btn btn-info w-100 m-1">
                        <fa-icon icon="edit" class="float-start mt-1" />
                        Update User Information
                    </button>
                    <button class="btn btn-info w-100 m-1">
                        <fa-icon icon="bell" class="float-start mt-1" />
                        Send Notification/Alert
                    </button>
                    <button class="btn btn-danger w-100 m-1">
                        <fa-icon icon="user-slash" class="float-start mt-1" />
                        Disable User
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import verify from '@/Modules/verify';
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    user: user;
    role: userRole;
    lastLogin?: {
        user_id: number;
        ip_address: string;
        created_at: string;
    };
}>();

/**
 * Resend the Welcome Email for the user to finish setting up their account
 */
const resendInvite = () => {
    verify({
        message: 'Resending the Welcome Email will create a new setup link and invalidate the original Welcome Email'
    }).then(res => {
        if(res) {
            router.get(route('admin.users.send-welcome', props.user.username), undefined, {
                onFinish: () => console.log('done'),
            });
        }
    });
}
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
