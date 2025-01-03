<template>
    <div class="flex flex-col h-screen z-0">
        <Head :title="appTitle" />
        <AppHeader @toggle-navbar="navbarHidden = !navbarHidden" />
        <div class="grow flex flex-row relative" style="border: 1px solid blue">
            <AppSideNav :navbar-hidden="navbarHidden" />
            <div class="flex grow" style="border: 1px solid purple">
                <div class="w-full flex flex-col">
                    <div class="grow">
                        <button
                            class="bg-blue-50 p-2"
                            @click="navbarHidden = !navbarHidden"
                        >
                            Toggle
                        </button>
                        <slot />
                    </div>
                    <div class="w-full h-12" style="border: 1px solid pink">
                        footer
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
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

<style>
#auth-layout-wrapper {
    min-height: 100vh;
    width: 100%;
}
</style>
