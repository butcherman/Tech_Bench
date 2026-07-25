<script setup lang="ts">
import AppHelp from "./AppHelp.vue";
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import UserAvatar from "./UserAvatar.vue";
import UserSettingsMenu from "@/features/user/components/UserSettingsMenu.vue";
import { about } from "@/wayfinder/routes";
import { dashboard } from "@/wayfinder/routes";
import { useAppData } from "@/core/state/appData.js";
import { ref } from "vue";

defineEmits<{
    toggleNavbar: [];
}>();

const { logo, appName } = useAppData();

const settingsOpen = ref<boolean>(false);
</script>

<template>
    <header
        class="fixed top-0 left-0 z-20 w-full h-14 bg-white flex flex-row border-b border-b-slate-200"
    >
        <Link :href="dashboard.url()">
            <img class="max-h-full px-4 py-1" :src="logo" />
        </Link>
        <h1 class="hidden md:flex md:grow items-center justify-center">
            {{ appName }}
        </h1>
        <div
            class="relative grow md:grow-0 flex items-center justify-end gap-2 me-2"
        >
            <AppHelp />
            <BaseBadge
                icon="circle-info"
                :href="about.url()"
                variant="info"
                circle
            />
            <UserAvatar @click="settingsOpen = true" />
            <UserSettingsMenu
                class="fixed top-13 right-5 bg-white"
                v-model="settingsOpen"
                v-on-click-outside="() => (settingsOpen = false)"
            />
            <BaseButton
                class="lg:hidden"
                icon="bars"
                variant="light"
                @click="$emit('toggleNavbar')"
            />
        </div>
    </header>
</template>
