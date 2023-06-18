<template>
    <Head title="User Details" />
    <Overlay :loading="loading">
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
                                        <span
                                            :title="role.description"
                                            v-tooltip
                                        >
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
                                <tr>
                                    <th class="text-end">Created On:</th>
                                    <td>{{ user.created_at }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">
                                        Profile Updated On:
                                    </th>
                                    <td>{{ user.updated_at }}</td>
                                </tr>
                                <tr v-if="user.deleted_at">
                                    <th class="text-end">Disabled On:</th>
                                    <td>{{ user.deleted_at }}</td>
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
                                Resend Invite Email
                            </button>
                            <button
                                class="btn btn-warning w-100 m-1"
                                @click="resetPasswordModal?.show()"
                            >
                                <fa-icon icon="key" class="float-start mt-1" />
                                Reset Password
                            </button>
                            <Link
                                as="button"
                                :href="
                                    $route('admin.users.edit', user.username)
                                "
                                class="btn btn-info w-100 m-1"
                            >
                                <fa-icon icon="edit" class="float-start mt-1" />
                                Update User Information
                            </Link>
                            <button
                                class="btn btn-info w-100 m-1"
                                @click="notificationModal?.show"
                            >
                                <fa-icon icon="bell" class="float-start mt-1" />
                                Send Notification
                            </button>
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
            <Modal ref="resetPasswordModal" title="Reset Password">
                <ResetPasswordForm
                    :user="user"
                    @completed="resetPasswordModal?.hide()"
                />
            </Modal>
            <Modal ref="notificationModal" title="Send User Notification/Alert">
                <NotificationForm
                    :user="user"
                    @completed="notificationModal?.hide"
                />
            </Modal>
        </div>
    </Overlay>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResetPasswordForm from "@/Forms/Admin/User/ResetPasswordForm.vue";
import NotificationForm from "@/Forms/Admin/User/NotificationForm.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import verify from "@/Modules/verify";
import { ref } from "vue";
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

const resetPasswordModal = ref<InstanceType<typeof Modal> | null>(null);
const notificationModal = ref<InstanceType<typeof Modal> | null>(null);
const loading = ref<boolean>(false);

/**
 * Resend the Welcome Email for the user to finish setting up their account
 */
const resendInvite = () => {
    verify({
        message:
            "Resending the Welcome Email will create a new setup link and invalidate the original Welcome Email",
    }).then((res) => {
        if (res) {
            loading.value = true;
            router.get(
                route("admin.users.send-welcome", props.user.username),
                undefined,
                {
                    onFinish: () => (loading.value = false),
                }
            );
        }
    });
};

/**
 * Immediately disable the user so it can no longer be accessed
 * Note:  does not delete any contributions user has made to Tech Bench
 */
const disableUser = () => {
    verify({
        message: "User will be immediately disabled",
    }).then((res) => {
        if (res) {
            loading.value = true;
            router.delete(route("admin.users.destroy", props.user.username), {
                onFinish: () => (loading.value = false),
            });
        }
    });
};

/**
 * Reactivate a previously disabled user
 */
const restoreUser = () => {
    verify({
        message: "Please Verify",
    }).then((res) => {
        if (res) {
            loading.value = true;
            router.get(
                route("admin.users.restore", props.user.username),
                undefined,
                {
                    onFinish: () => (loading.value = false),
                }
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
