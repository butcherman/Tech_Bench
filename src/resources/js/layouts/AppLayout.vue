<script setup lang="ts">
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed, ref, useTemplateRef } from "vue";
import AppHeader from "./components/AppHeader.vue";

const pageTitle = computed<string | undefined>(
    () =>
        usePage().props.breadcrumbs.find((crumb) => crumb.is_current_page)
            ?.title,
);
</script>

<template>
    <div class="h-screen flex flex-col">
        <Head :title="pageTitle" />
        <AppHeader />
    </div>
</template>
