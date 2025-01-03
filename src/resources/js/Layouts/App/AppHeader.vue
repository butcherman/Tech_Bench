<template>
    <header class="flex h-14 sticky">
        <div class="w-full flex">
            <Link :href="$route('dashboard')" class="flex">
                <img :src="app.logo" class="object-contain h-75 md:ms-5 p-1" />
            </Link>
            <h1 class="text-center w-full py-2 -md:hidden">Tech Bench</h1>
            <div class="flex flex-row my-auto mx-2">
                <div class="flex flex-row my-auto mx-2">
                    <AppHelp v-tooltip.left="'Help'" />
                    <Link
                        :href="$route('about')"
                        v-tooltip.left="'About Tech Bench'"
                    >
                        <fa-icon
                            icon="fa-circle-info"
                            class="mx-1 text-muted"
                        />
                    </Link>
                </div>
                <UserAvatar />
            </div>
            <BaseButton
                icon="bars"
                variant="light"
                class="my-2 mx-2 md:hidden"
                @click="$emit('toggle-navbar')"
            />
        </div>
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
