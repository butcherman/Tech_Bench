<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResetUserPasswordForm from "@/Forms/Admin/User/ResetUserPasswordForm.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";
import { useAuthStore } from "@/Stores/AuthStore";
import { useTemplateRef } from "vue";

const props = defineProps<{
    user: user;
    role: userRole;
    lastLogin: {
        ip_address: string;
        created_at: string;
    } | null;
    thirtyDayCount: number;
    allowTwoFa: boolean;
    allowSaveDevice: boolean;
}>();

const resetModal = useTemplateRef("reset-password-modal");
const auth = useAuthStore();

/**
 * Destructure and re-assemble data for view tables
 */
const {
    username,
    first_name,
    last_name,
    email,
    role_name,
    created_at,
    updated_at,
} = props.user;

const userData = { username, first_name, last_name, email, role_name };
const userActivity = {
    user_created: created_at,
    profile_last_update: updated_at,
    last_login_date: props.lastLogin?.created_at ?? "Never",
    logins_last_30_days: props.thirtyDayCount,
};

/**
 * Email a link to the user to allow them to reset their own password.
 */
const sendResetLink = (): void => {
    router.post(route("admin.user.password-link"), { email: props.user.email });
};

/**
 * Disable User and log them out of all sessions.
 */
const disableUser = (): void => {
    verifyModal(`${props.user.full_name} will be immediately disabled`).then(
        (res) => {
            if (res) {
                router.delete(route("admin.user.destroy", props.user.username));
            }
        }
    );
};

/**
 * Restore a Disabled user
 */
const restoreUser = (): void => {
    verifyModal(`${props.user.full_name} will be immediately enabled`).then(
        (res) => {
            if (res) {
                router.get(route("admin.user.restore", props.user.username));
            }
        }
    );
};

/**
 * Remove all 2FA settings and devices so user has to setup again.
 */
const resetTwoFa = (): void => {
    // verifyModal('Two Factor Settings will be reset and all ')

    let verifyMessage = "Two Factor Settings will be reset";
    if (props.allowSaveDevice) {
        verifyMessage += " and all Saved Devices will be deleted";
    }

    verifyModal(verifyMessage).then((res) => {
        if (res) {
            router.put(
                route("admin.user.two-factor.reset", props.user.username)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="grid lg:grid-cols-3 gap-3 my-3">
            <Card title="User Details" class="lg:col-span-2">
                <TableStacked class="w-full" :items="userData" />
            </Card>
            <Card>
                <template v-if="auth.user?.username == user.username">
                    <div class="h-full flex flex-col justify-center">
                        <h4 class="text-center text-muted">
                            Please visit the User Settings Page to make changes
                            to your own account
                        </h4>
                    </div>
                </template>
                <template v-else-if="!user.deleted_at">
                    <div class="h-full flex flex-col justify-center">
                        <BaseButton
                            class="w-full mb-1"
                            text="Reset Users Password"
                            variant="warning"
                            @click="resetModal?.show()"
                        />
                        <BaseButton
                            v-if="allowTwoFa"
                            class="w-full mb-1"
                            text="Reset 2FA Settings"
                            variant="info"
                            @click="resetTwoFa()"
                        />
                        <BaseButton
                            class="w-full mb-1"
                            text="Send Reset Password Link"
                            variant="help"
                            @click="sendResetLink()"
                        />
                        <BaseButton
                            class="w-full mb-1"
                            text="Update User Information"
                            :href="$route('admin.user.edit', user.username)"
                        />
                        <BaseButton
                            class="w-full mb-1"
                            text="Disable User"
                            variant="danger"
                            @click="disableUser()"
                        />
                    </div>
                </template>
                <template v-else>
                    <BaseButton
                        class="w-full my-2"
                        text="Enable User"
                        variant="error"
                        @click="restoreUser()"
                    />
                </template>
            </Card>
        </div>
        <div>
            <Card title="User Activity" class="flex justify-center">
                <TableStacked
                    :items="userActivity"
                    class="w-full lg:w-3/4 mx-auto"
                    title-case
                />
            </Card>
        </div>
        <Modal title="Reset Password" ref="reset-password-modal">
            <ResetUserPasswordForm :user="user" @success="resetModal?.hide()" />
        </Modal>
    </div>
</template>
