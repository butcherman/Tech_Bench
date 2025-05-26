<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>

<template>
    <header
        id="kb-header"
        class="h-14 w-full bg-white border-b z-20 fixed top-0 left-0 flex flex-row"
    >
        <Link :href="$route('publicTips.index')" class="flex object-contain">
            <img id="app-header-logo" class="p-1 md:ms-5" :src="app.logo" />
        </Link>
        <h1 id="kb-header-title" class="w-full py-2 -md:hidden text-center">
            {{ app.companyName }}
        </h1>
    </header>
</template>
