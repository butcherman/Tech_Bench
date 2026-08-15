<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import UserAccountForm from "../forms/UserAccountForm.vue";
import UserSettingsForm from "../forms/UserSettingsForm.vue";

const props = defineProps<{
    user: User;
    settings: UserSettings[];
}>();
</script>

<template>
    <div class="flex flex-col md:flex-row gap-3">
        <Card title="My Account">
            <UserAccountForm :user="user" />
        </Card>
        <Card>
            <UserSettingsForm :user="user" :settings="settings" />
        </Card>
    </div>
</template>
