<template>
    <div v-if="app.flash.length">
        <!-- <h1
            v-for="flash in app.flash"
            border="start"
            class="text-center"
            elevation="4"
            :color="getFlashColor(flash.type)"
            :icon="getFlashIcon(flash.type)"
            :text="flash.message"
        /> -->
        <h1 v-for="flash in app.flash">{{ flash }}</h1>
    </div>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";
// import { getFlashColor, getFlashIcon } from "@/Composables/Flash";

const app = useAppStore();
</script>
