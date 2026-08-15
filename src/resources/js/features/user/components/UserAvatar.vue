<script setup lang="ts">
import UserAvatarSettingsMenu from "./UserAvatarSettingsMenu.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { useUserState } from "../state/userState.js";

const { authorizedUser } = useUserState();
const settingsOpen = ref(false);

/**
 * Close the settings menu when a link is clicked
 */
router.on("start", () => (settingsOpen.value = false));
</script>

<template>
    <div>
        <div
            class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-slate-200 rounded-full pointer"
            @click="settingsOpen = true"
        >
            <span class="font-medium text-body">
                {{ authorizedUser?.initials }}
            </span>
        </div>
        <UserAvatarSettingsMenu
            :open="settingsOpen"
            v-on-click-outside="() => (settingsOpen = false)"
        />
    </div>
</template>
