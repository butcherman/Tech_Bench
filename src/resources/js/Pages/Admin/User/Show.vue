<template>
    <div>
        <Head title="User Details" />
        <div
            v-if="app.user?.username === user.username"
            class="alert alert-danger text-center"
        >
            Please visit the Settings Page to make changes to your own account
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">User Details</div>
                        <div class="table-responsive">
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
                                        <td>{{ role.name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="app.user?.username !== user.username" class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Actions</div>
                        <template v-if="!user.deleted_at">
                            <button
                                v-if="!lastLogin"
                                class="btn btn-primary w-100 m-1"
                                @click="resendInvite"
                            >
                                <fa-icon
                                    icon="envelope"
                                    class="float-start mt-1"
                                />
                                Resend Welcome Email
                            </button>
                            <button
                                class="btn btn-warning w-100 m-1"
                                @click="sendResetLink"
                            >
                                <fa-icon icon="key" class="float-start mt-1" />
                                Send Reset Password Link
                            </button>
                            <Link
                                as="button"
                                :href="$route('admin.user.edit', user.username)"
                                class="btn btn-info w-100 m-1"
                            >
                                <fa-icon icon="edit" class="float-start mt-1" />
                                Update User Information
                            </Link>
                            <button
                                class="btn btn-danger w-100 m-1"
                                @click="disableUser"
                            >
                                <fa-icon
                                    icon="user-slash"
                                    class="float-start mt-1"
                                />
                                Disable User
                            </button>
                        </template>
                        <template v-else>
                            <button
                                class="btn btn-danger w-100 m-1"
                                @click="restoreUser"
                            >
                                <fa-icon
                                    icon="unlock-alt"
                                    class="float-start mt-1"
                                />
                                Enable User
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">User Activity</div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="text-end">User Created:</th>
                                        <td>{{ user.created_at }}</td>
                                    </tr>
                                    <tr v-if="user.deleted_at">
                                        <th class="text-end">
                                            Deactivated Date
                                        </th>
                                        <td>{{ user.deleted_at }}</td>
                                    </tr>
                                    <tr v-if="lastLogin">
                                        <th class="text-end">
                                            Profile Last Updated:
                                        </th>
                                        <td>{{ user.updated_at }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">
                                            Last Login Date:
                                        </th>
                                        <td>
                                            {{
                                                lastLogin?.created_at ||
                                                "User Has Never Logged In"
                                            }}
                                        </td>
                                    </tr>
                                    <tr v-if="lastLogin">
                                        <th class="text-end">
                                            # Logins Last 30 Days:
                                        </th>
                                        <td>{{ thirtyDayCount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import verifyModal from "@/Modules/verifyModal";
import { useAppStore } from "@/Store/AppStore";
import { router, useForm } from "@inertiajs/vue3";

const props = defineProps<{
    user: user;
    role: userRole;
    lastLogin: {
        ip_address: string;
        created_at: string;
    } | null;
    thirtyDayCount: number;
}>();

const app = useAppStore();

const resendInvite = () => {
    verifyModal(
        `Resending the Welcome Email will create a new setup link and invalidate
         the original Welcome Email`
    ).then((res) => {
        if (res) {
            router.get(route("admin.user.send-welcome", props.user.username));
        }
    });
};

const sendResetLink = () => {
    verifyModal(
        `This will send the user an email with a link and instructions for resetting
         their password`
    ).then((res) => {
        if (res) {
            const formData = useForm({ email: props.user.email });
            formData.post(route("admin.user.password-link"));
        }
    });
};

const disableUser = () => {
    verifyModal(`${props.user.full_name} will be immediately disabled`).then(
        (res) => {
            console.log(res);
            if (res) {
                router.delete(route("admin.user.destroy", props.user.username));
            }
        }
    );
};

const restoreUser = () => {
    verifyModal(`${props.user.full_name} will be immediately enabled`).then(
        (res) => {
            if (res) {
                router.get(route("admin.user.restore", props.user.username));
            }
        }
    );
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
