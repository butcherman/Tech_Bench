<script setup lang="ts">
import AdminUserSettingsForm from "@/features/user/forms/AdminUserSettingsForm.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";

defineProps<{
    autoLogoutTimer: number;
    twoFa: TwoFaConfig;
    oath: OathConfig;
    roleList: UserRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>
            <AdminUserSettingsForm
                :auto-logout-timer="autoLogoutTimer"
                :twoFa="twoFa"
                :oath="oath"
                :role-list="roleList"
            />
        </Card>
    </div>
</template>
