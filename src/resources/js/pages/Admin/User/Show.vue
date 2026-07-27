<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import TableStacked from "@/features/dataTable/TableStacked.vue";
import UserAdministrationActions from "@/features/user/components/UserAdministrationActions.vue";
import { useUserAuth } from "@/core/state/userAuth";

const props = defineProps<{
    user: User;
    role: UserRole;
    lastLogin: {
        ip_address: string;
        created_at: string;
    } | null;
    thirtyDayCount: number;
    allowTwoFa: boolean;
    allowSaveDevice: boolean;
}>();

const { authorizedUser } = useUserAuth();

const userActivity = {
    user_created: props.user.created_at,
    profile_last_update: props.user.updated_at,
    last_login_date: props.lastLogin?.created_at ?? "Never",
    logins_last_30_days: props.thirtyDayCount,
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-3">
        <div class="grid md:grid-cols-3 gap-3">
            <Card title="User Details" size="large" class="md:col-span-2">
                <div class="flex justify-center">
                    <TableStacked
                        class="w-full"
                        :items="user"
                        :skip="[
                            'role_id',
                            'two_factor_confirmed_at',
                            'deleted_at',
                            'created_at',
                            'updated_at',
                            'initials',
                            'full_name',
                        ]"
                    />
                </div>
            </Card>
            <Card size="large">
                <div
                    v-if="user.username === authorizedUser?.username"
                    class="h-full flex flex-col justify-center"
                >
                    <h4 class="text-center text-muted">
                        Please visit the User Settings Page to make changes to
                        your own account
                    </h4>
                </div>
                <UserAdministrationActions v-else v-bind="props" />
            </Card>
        </div>
        <div class="flex justify-center">
            <Card title="User Activity">
                <div class="flex justify-center">
                    <TableStacked
                        :items="userActivity"
                        :only="['created_at', '']"
                        class="w-full"
                        title-case
                    />
                </div>
            </Card>
        </div>
    </div>
</template>
