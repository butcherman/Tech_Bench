<script setup lang="ts">
import AppSearchForm from "./AppSearchForm.vue";
import AuthorizedUser from "./AuthorizedUser.vue";
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { about } from "@/wayfinder/routes";

defineEmits<{
    toggleNavbar: [];
}>();
</script>

<template>
    <header
        class="fixed top-0 left-0 z-20 w-full h-14 bg-gray-100 dark:bg-gray-500 flex flex-row-reverse gap-4 border-b border-b-slate-200"
    >
        <div class="grow md:grow-0 flex items-center justify-end gap-2 me-2">
            <BaseBadge
                icon="circle-info"
                :href="about.url()"
                size="sm"
                variant="info"
                circle
            />
            <AuthorizedUser />
            <BaseButton
                class="lg:hidden"
                icon="bars"
                variant="light"
                size="sm"
                @click="$emit('toggleNavbar')"
            />
        </div>
        <div class="grow lg:ms-64">
            <AppSearchForm class="mx-2 lg:w-1/2" />
        </div>
    </header>
</template>
