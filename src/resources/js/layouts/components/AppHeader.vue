<script setup lang="ts">
import AuthorizedUser from "./AuthorizedUser.vue";
import AppSearchForm from "./AppSearchForm.vue";
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { about } from "@/wayfinder/routes";

defineEmits<{
    toggleNavbar: [];
}>();
</script>

<template>
    <header
        class="flex h-16 items-center justify-between border-b border-slate-300 bg-gray-200 px-6 dark:bg-gray-800"
    >
        <AppSearchForm class="md:w-150" />
        <div class="flex gap-2">
            <div class="items-center hidden lg:flex">
                <BaseBadge
                    icon="circle-info"
                    :href="about.url()"
                    variant="info"
                    size="sm"
                    circle
                />
            </div>
            <AuthorizedUser />
            <div class="flex items-center">
                <BaseButton
                    class="lg:hidden"
                    icon="bars"
                    variant="light"
                    size="sm"
                    @click="$emit('toggleNavbar')"
                />
            </div>
        </div>
    </header>
</template>
