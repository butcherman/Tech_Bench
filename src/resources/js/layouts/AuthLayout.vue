<script setup lang="ts">
import LoginLogo from "@/features/auth/components/LoginLogo.vue";
import FlashAlert from "./components/FlashAlert.vue";

const props = defineProps<{
    homeLinks: LinkList[];
    publicLink: LinkList | false;
    welcomeMessage?: string;
}>();
</script>

<template>
    <div>
        <FlashAlert />
        <div
            class="flex flex-col md:flex-row h-screen justify-center bg-content m-2 md:m-0"
        >
            <div
                class="md:w-9/20 bg-white md:bg-sidebar-header flex items-center justify-center pb-4 md:pb-0"
            >
                <LoginLogo :home-links :public-link :welcome-message />
            </div>
            <div
                class="md:w-11/20 bg-white flex flex-col justify-center items-center px-4 border-t border-slate-300 md:border-0"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
