<template>
    <div id="app-layout-wrapper">
        <Head :title="appTitle" />
        <AppHeader
            id="app-header"
            @toggle-navbar="navbarHidden = !navbarHidden"
        />
        <AppSideNav id="app-navbar" :navbar-hidden="navbarHidden" />
        <section id="app-content">
            <div id="app-page-wrapper">
                <slot />
            </div>
            <AppFooter />
        </section>
    </div>
</template>

<script setup lang="ts">
import AppFooter from "./AppFooter.vue";
import AppHeader from "./AppHeader.vue";
import AppSideNav from "./AppSideNav.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

/*
|-------------------------------------------------------------------------------
| Dynamic Navbar Controls
|-------------------------------------------------------------------------------
*/
const navbarHidden = ref<boolean | null>(true);
router.on("navigate", () => (navbarHidden.value = true));

/*
|-------------------------------------------------------------------------------
| Breadcrumbs are imported to set the Title Element
|-------------------------------------------------------------------------------
*/
const breadcrumbs = computed<breadcrumbs[]>(
    () => usePage<pageProps>().props.breadcrumbs
);
const appTitle = computed<string | undefined>(
    () => breadcrumbs.value.at(-1)?.title
);
</script>
