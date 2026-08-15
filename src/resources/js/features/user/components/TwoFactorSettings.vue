<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import TwoFactorRecoveryCodes from "./TwoFactorRecoveryCodes.vue";
import TwoFactorVerifiedDevices from "./TwoFactorVerifiedDevices.vue";

const props = defineProps<{
    user: User;
    devices: UserDevice[];
}>();
</script>

<template>
    <Card title="Two Factor Authentication">
        <template #append-title>
            <TwoFactorRecoveryCodes />
        </template>
        <div>authenticator config</div>
        <TwoFactorVerifiedDevices :user="user" :devices="devices" />
    </Card>
</template>
