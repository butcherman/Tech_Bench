<template>
    <div class="grid md:grid-cols-2 gap-2">
        <Card title="My Account">
            <UserAccountForm :user="current_user" />
        </Card>
        <Card title="My Settings">
            <UserSettingsForm :user="current_user" :settings="settings" />
        </Card>
        <Card class="md:col-span-2" title="My Devices">
            <UserDevices :user="current_user" :devices="devices" />
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import UserSettingsForm from "@/Forms/User/UserSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";
import UserDevices from "@/Components/User/UserDevices.vue";

defineProps<{
    current_user: user;
    twoFaEnabled: boolean;
    devices: userDevice[];
    settings: userSettings[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
