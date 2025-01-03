<template>
    <header>
        <Link :href="$route('dashboard')" class="flex object-contain">
            <img id="app-header-logo" :src="app.logo" />
        </Link>
        <h1 id="app-header-title">Tech Bench</h1>
        <div id="app-header-actions">
            <div>
                <AppHelp v-tooltip.left="'Help'" />
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

<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import UserAvatar from "./UserAvatar.vue";
import AppHelp from "./AppHelp.vue";
import { useAppStore } from "@/Stores/AppStore";

defineEmits(["toggle-navbar"]);

const app = useAppStore();
</script>
