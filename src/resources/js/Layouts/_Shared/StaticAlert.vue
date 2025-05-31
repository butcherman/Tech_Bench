<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>

<template>
    <div>
        <div
            v-for="err in app.errorList"
            class="bg-red-600 flex py-2 px-4 rounded-lg justify-between text-white"
        >
            <div><fa-icon icon="triangle-exclamation" /></div>
            <div>{{ err }}</div>
            <div><fa-icon icon="triangle-exclamation" /></div>
        </div>
    </div>
</template>
