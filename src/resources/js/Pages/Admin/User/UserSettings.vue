<template>
    <Card class="tb-card">
        <UserConfigurationForm
            :auto-logout-timer="autoLogoutTimer"
            :twoFa="twoFa"
            :oath="oath"
            :role-list="roleList"
        />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import UserConfigurationForm from "@/Forms/Admin/User/UserConfigurationForm.vue";

defineProps<{
    autoLogoutTimer: number;
    twoFa: twoFaConfig;
    oath: oathConfig;
    roleList: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
