<script setup lang="ts">
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed, ref, useTemplateRef } from "vue";
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";

/*
|-------------------------------------------------------------------------------
| Page Title
|-------------------------------------------------------------------------------
*/
const pageTitle = computed<string | undefined>(
    () =>
        usePage().props.breadcrumbs.find((crumb) => crumb.is_current_page)
            ?.title,
);
</script>

<template>
    <div class="h-screen flex flex-col">
        <Head :title="pageTitle" />

        <div class="flex h-screen overflow-hidden bg-gray-100 dark:bg-gray-900">
            <aside
                class="hidden w-64 shrink-0 bg-white dark:bg-gray-800 lg:block"
            >
                <div class="p-4 font-bold text-xl">Logo</div>
                <nav class="space-y-1 p-4">
                    <a
                        href="#"
                        class="block rounded px-4 py-2 bg-gray-200 dark:bg-gray-700"
                        >Dashboard</a
                    >
                    <a
                        href="#"
                        class="block rounded px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >Users</a
                    >
                </nav>
            </aside>

            <div class="flex flex-1 flex-col overflow-hidden">
                <AppHeader />
                <main class="flex-1 overflow-y-auto overflow-x-hidden p-4">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
