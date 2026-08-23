<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import SecuritySettingsForm from "@/features/auth/forms/SecuritySettingsForm.vue";

defineProps<{
    autoLogoutTimer: number;
    twoFa: MultiFactorConfig;
    oath: OathConfig;
    roleList: UserRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="User Security Settings">
            <SecuritySettingsForm
                :auto-logout-timer="autoLogoutTimer"
                :twoFa="twoFa"
                :oath="oath"
                :role-list="roleList"
            />
        </Card>
    </div>
</template>
