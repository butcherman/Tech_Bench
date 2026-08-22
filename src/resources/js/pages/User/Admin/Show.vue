<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import UserAdministrationAccountActivity from "@/features/user/components/UserAdministrationAccountActivity.vue";
import UserAdministrationActions from "@/features/user/components/UserAdministrationActions.vue";
import UserAdministrationProfile from "@/features/user/components/UserAdministrationProfile.vue";
import UserAdministrationSecurity from "@/features/user/components/UserAdministrationSecurity.vue";

const props = defineProps<{
    user: User;
    lastLogin: {
        ip_address: string;
        created_at: string;
    } | null;
    thirtyDayCount: number;
    allowTwoFa: boolean;
    allowSaveDevice: boolean;
    savedDevicesCount: number;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-3 items-center">
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-3/4">
            <div class="basis-2/3 flex flex-col gap-3">
                <UserAdministrationProfile :user="user" />
                <UserAdministrationSecurity
                    v-if="allowTwoFa"
                    :user="user"
                    :allow-two-fa="allowTwoFa"
                    :allow-save-device="allowSaveDevice"
                    :saved-devices-count="savedDevicesCount"
                />
            </div>
            <UserAdministrationActions :user="user" />
        </div>
        <div class="w-full md:w-3/4">
            <UserAdministrationAccountActivity
                :user="user"
                :last-login="lastLogin"
                :thirty-day-count="thirtyDayCount"
            />
        </div>
    </div>
</template>
