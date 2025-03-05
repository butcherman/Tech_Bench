<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import UserAvatar from "./UserAvatar.vue";
// import AppHelp from "./AppHelp.vue";
import { useAppStore } from "@/Stores/AppStore";

defineEmits(["toggle-navbar"]);

const app = useAppStore();
</script>

<template>
    <header id="app-header">
        <Link :href="$route('dashboard')" class="flex object-contain">
            <img id="app-header-logo" :src="app.logo" />
        </Link>
        <h1 id="app-header-title">Tech Bench</h1>
        <div id="app-header-actions">
            <div>
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
            icon="bars"
            variant="light"
            @click="$emit('toggle-navbar')"
        />
    </header>
</template>

<style>
#app-header {
    @apply h-14 w-full bg-white border-b z-20;
    @apply fixed top-0 left-0;
    @apply flex flex-row;
}

#app-header-logo {
    @apply p-1 md:ms-5;
}

#app-header-title {
    @apply w-full py-2 -md:hidden;
    @apply text-center;
}

#app-header-actions {
    @apply flex flex-row grow justify-end;
    @apply my-auto;
    div {
        @apply flex flex-row my-auto mx-2;
    }
}

#app-header-nav-toggler {
    @apply my-2 mx-2;
    @apply md:hidden;
}
</style>
