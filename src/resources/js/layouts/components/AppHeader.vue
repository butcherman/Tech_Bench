<script setup lang="ts">
import AuthorizedUser from "./AuthorizedUser.vue";
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import AppSearchForm from "./AppSearchForm.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { about } from "@/wayfinder/routes";

defineEmits<{
    toggleNavbar: [];
}>();
</script>

<template>
    <header
        class="fixed top-0 left-0 z-20 w-full h-14 bg-header flex gap-4 px-4 border-b border-slate-300"
    >
        <div class="flex-1 lg:ms-64">
            <AppSearchForm />
        </div>
        <div class="flex items-center gap-2">
            <BaseBadge
                class="text-blue-300"
                icon="circle-info"
                :href="about.url()"
                v-tooltip="'About'"
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
    </header>
</template>
