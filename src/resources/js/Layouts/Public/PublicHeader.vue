<script setup lang="ts">
import AppHelp from "../App/AppHelp.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import UserAvatar from "../App/UserAvatar.vue";
import { useAppStore } from "@/Stores/AppStore";
import { useAuthStore } from "@/Stores/AuthStore";

const app = useAppStore();
const auth = useAuthStore();
</script>

<template>
    <header
        class="h-14 bg-white border-b border-b-slate-300 z-20 fixed top-0 left-0 w-full flex"
    >
        <Link :href="$route('home')" class="flex object-contain">
            <img class="p-1 md:ms-5" :src="app.logo" />
        </Link>
        <h1 class="grow text-center py-2">{{ app.companyName }}</h1>
        <div v-if="auth.user" class="flex items-center gap-2">
            <AppHelp />
            <BaseBadge
                :href="$route('about')"
                class="p-1! text-white bg-slate-600"
                icon="circle-info"
                v-tooltip.left="'About Tech Bench'"
            />
            <UserAvatar />
        </div>
    </header>
</template>
