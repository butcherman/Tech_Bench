<template>
    <v-app id="app-layout-wrapper">
        <AppHeader />
        <slot />
    </v-app>
</template>

<script setup lang="ts">
import AppHeader from "./AppHeader.vue";
</script>
