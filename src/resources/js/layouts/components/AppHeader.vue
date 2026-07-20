<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { dashboard } from "@/wayfinder/routes";
import { useAppData } from "@/core/store/appData";

defineEmits<{
    toggleNavbar: [];
}>();

const { logo, appName } = useAppData();
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
        <div class="grow md:grow-0 flex items-center justify-end gap-2 me-2">
            help | about | user avatar
            <BaseButton
                class="lg:hidden"
                icon="bars"
                variant="light"
                @click="$emit('toggleNavbar')"
            />
        </div>
    </header>
</template>
