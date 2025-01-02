<template>
    <div>
        <h1 class="text-center text-white">
            {{ app.name }}
        </h1>
        <div class="m-2 p-4 bg-white rounded">
            <img :src="app.logo" class="justify-self-center" />
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>
