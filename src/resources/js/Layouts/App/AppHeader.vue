<template>
    <v-app-bar :elevation="0" class="border-b">
        <template #prepend>
            <img :src="app.logo" class="object-contain h-75 md:ms-5" />
        </template>
        <v-app-bar-title>
            <h1 class="text-center text-3xl font-bold">Tech Bench</h1>
        </v-app-bar-title>
        <template #append>
            <Link href="#" class="mx-1 text-muted">
                <font-awesome-icon icon="circle-question" />
            </Link>
            <Link href="#" class="mx-1 text-muted">
                <font-awesome-icon icon="circle-info" />
            </Link>
            <UserAvatar />
            <v-app-bar-nav-icon
                class="d-md-none"
                @click="$emit('toggle-navbar')"
            />
        </template>
    </v-app-bar>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";
import UserAvatar from "./UserAvatar.vue";

defineEmits(["toggle-navbar"]);

const app = useAppStore();
</script>
