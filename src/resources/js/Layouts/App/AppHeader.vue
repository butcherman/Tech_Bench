<script setup lang="ts">
import AppHelp from "./AppHelp.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import UserAvatar from "./UserAvatar.vue";
import { useAppStore } from "@/Stores/AppStore";

defineEmits<{
    "toggle-navbar": [];
}>();

const app = useAppStore();
</script>

<template>
    <header class="fixed top-0 left-0 z-20 w-full h-14 bg-white flex flex-row">
        <Link :href="$route('dashboard')">
            <img class="max-h-full px-4 py-1" :src="app.logo" />
        </Link>
        <h1 class="hidden md:flex md:grow items-center justify-center">
            Tech Bench
        </h1>
        <div class="grow md:grow-0 flex items-center justify-end gap-2 me-2">
            <AppHelp />
            <BaseBadge
                :href="$route('about')"
                class="p-1! text-white bg-slate-600"
                icon="circle-info"
                v-tooltip.left="'About Tech Bench'"
            />
            <UserAvatar />
            <BaseButton
                class="md:hidden pointer"
                icon="bars"
                variant="light"
                @click="$emit('toggle-navbar')"
            />
        </div>
    </header>
</template>
