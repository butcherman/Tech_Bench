<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import UserAvatar from "./UserAvatar.vue";
// import AppHelp from "./AppHelp.vue";
import { useAppStore } from "@/Stores/AppStore";

defineEmits<{
    "toggle-navbar": [];
}>();

const app = useAppStore();
</script>

<template>
    <header
        id="app-header"
        class="h-14 w-full bg-white border-b z-20 fixed top-0 left-0 flex flex-row"
    >
        <Link :href="$route('dashboard')" class="flex object-contain">
            <img id="app-header-logo" class="p-1 md:ms-5" :src="app.logo" />
        </Link>
        <h1 id="app-header-title" class="w-full py-2 -md:hidden text-center">
            Tech Bench
        </h1>
        <div
            id="app-header-actions"
            class="flex flex-row grow justify-end my-auto mx-2"
        >
            <div class="flex flex-row my-auto mx-2">
                <!-- <AppHelp v-tooltip.left="'Help'" /> -->
                <Link
                    :href="$route('about')"
                    v-tooltip.left="'About Tech Bench'"
                >
                    <fa-icon icon="fa-circle-info" class="mx-1 text-muted" />
                </Link>
            </div>
            <UserAvatar />
        </div>
        <BaseButton
            id="app-header-nav-toggler"
            class="my-2 mx-2 md:hidden"
            icon="bars"
            variant="light"
            @click="$emit('toggle-navbar')"
        />
    </header>
</template>
