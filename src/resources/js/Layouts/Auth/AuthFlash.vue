<template>
    <div v-if="app.flash.length">
        <v-alert
            v-for="flash in app.flash"
            border="start"
            class="text-center"
            elevation="4"
            :color="getFlashColor(flash.type)"
            :icon="getFlashIcon(flash.type)"
            :text="flash.message"
        />
    </div>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();

const getFlashColor = (flashType: string): string => {
    return flashType === "status" ? "info" : flashType;
};

const getFlashIcon = (flashType: string): string => {
    return flashType === "status" ? "$info" : "$" + flashType;
};
</script>
