<template>
    <div class="grid md:grid-cols-2 gap-4">
        <Card title="User Details">
            <TableStacked class="w-full" :items="userData" title-case />
        </Card>
        <Card title="Actions">
            <template v-if="app.user?.username == user.username">
                <h4 class="text-center text-muted">
                    Please visit the User Settings Page to make changes to your
                    own account
                </h4>
            </template>
            <template v-else-if="!user.deleted_at">
                <Button
                    class="w-full my-2"
                    text="Send Reset Password Link"
                    variant="warning"
                    @click="resendInvite"
                />
                <Button
                    class="w-full my-2"
                    text="Update User Information"
                    :href="$route('admin.user.edit', user.username)"
                />
                <Button
                    class="w-full my-2"
                    text="Disable User"
                    variant="danger"
                    @click="disableUser"
                />
            </template>
            <template v-else>
                <Button
                    class="w-full my-2"
                    text="Enable User"
                    variant="error"
                    @click="restoreUser"
                />
            </template>
        </Card>
        <div class="md:col-span-2 justify-items-center">
            <Card title="User Activity" class="w-full md:w-3/4">
                <TableStacked
                    class="place-self-center"
                    :items="userActivity"
                    title-case
                />
            </Card>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import Button from "@/Components/_Base/Buttons/BaseButton.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";
import { useAppStore } from "@/Stores/AppStore";

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
 * Resend a Welcome Email to the user with a link to setup their password
 */
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

/**
 * Disable User and log them out of all sessions.
 */
const disableUser = () => {
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
