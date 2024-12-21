<template>
    <v-app id="app-layout-wrapper">
        <AppHeader @toggle-navbar="navbarActive = !navbarActive" />
        <AppSideNav :navbar-active="navbarActive" />
        <v-main class="bg-stone-200">
            <div class="p-4 mb-auto">
                <slot />
            </div>
            <AppFooter />
        </v-main>
    </v-app>
</template>

<script setup lang="ts">
import { ref } from "vue";
import AppHeader from "./AppHeader.vue";
import AppSideNav from "./AppSideNav.vue";
import AppFooter from "./AppFooter.vue";

const navbarActive = ref(null);
</script>
