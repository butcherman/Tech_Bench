<script setup lang="ts">
import UserAvatar from "@/features/user/components/UserAvatar.vue";
import UserAvatarSettingsMenu from "@/features/user/components/UserAvatarSettingsMenu.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { useUserState } from "@/features/user/state/userState";

const { authorizedUser } = useUserState();

const settingsOpen = ref(false);

/**
 * Close the settings menu when a link is clicked
 */
router.on("start", () => (settingsOpen.value = false));
</script>

<template>
    <div v-if="authorizedUser">
        <UserAvatar
            class="pointer"
            size="sm"
            :user="authorizedUser"
            @click="settingsOpen = true"
        />
        <UserAvatarSettingsMenu
            :open="settingsOpen"
            v-on-click-outside="() => (settingsOpen = false)"
        />
    </div>
</template>
