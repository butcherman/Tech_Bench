<script setup lang="ts">
import UserPreferencesForm from "../forms/UserPreferencesForm.vue";

const props = defineProps<{
    current_user: User;
    settings: UserSettings[];
}>();
</script>

<template>
    <div class="h-full">
        <UserPreferencesForm :user="current_user" :settings="settings" />
    </div>
</template>
