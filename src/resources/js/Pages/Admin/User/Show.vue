<template>
    <div class="grid md:grid-cols-2 gap-4">
        <Card title="User Details">
            <TableStacked class="w-full" :rows="userData" title-case />
        </Card>
        <Card title="Actions">
            <template v-if="!user.deleted_at">
                <Button
                    class="w-full my-2"
                    text="Send Reset Password Link"
                    color="yellow-darken-3"
                    @click="resendInvite"
                />
                <Button
                    class="w-full my-2"
                    text="Update User Information"
                    color="cyan-accent-3"
                    :href="$route('admin.user.edit', user.username)"
                />
                <Button
                    class="w-full my-2"
                    text="Disable User"
                    color="red"
                    @click="disableUser"
                />
            </template>
            <template v-else>
                <Button
                    class="w-full my-2"
                    text="Enable User"
                    color="red"
                    @click="restoreUser"
                />
            </template>
        </Card>
        <div class="md:col-span-2 justify-items-center">
            <Card title="User Activity" class="w-full md:w-3/4">
                <TableStacked class="w-full" :rows="userActivity" title-case />
            </Card>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import Button from "@/Components/_Base/Buttons/BaseButton.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import VerifyModal from "@/Modules/VerifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    user: user;
    role: userRole;
    lastLogin: {
        ip_address: string;
        created_at: string;
    } | null;
    thirtyDayCount: number;
}>();

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
    VerifyModal(
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
    VerifyModal(`${props.user.full_name} will be immediately disabled`).then(
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
    VerifyModal(`${props.user.full_name} will be immediately enabled`).then(
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
