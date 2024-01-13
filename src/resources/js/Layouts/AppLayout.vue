<template>
    <div id="app-layout" class="container-fluid h-100 p-0 m-0">
        <AppHeader @navbar-toggle="navbarActive = !navbarActive" />
        <div id="app-wrapper" class="container-fluid">
            <AppSideNav :active="navbarActive" />
            <div id="content-wrapper">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppHeader from "@/Layouts/AppLayout/AppHeader.vue";
import AppSideNav from "@/Layouts/AppLayout/AppSideNav.vue";
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import "../../scss/Layouts/appLayout.scss";

/**
 * Navbar shown state
 */
const navbarActive = ref(false);
/**
 * Close the navbar when routing to new page
 */
router.on("navigate", () => (navbarActive.value = false));
</script>
