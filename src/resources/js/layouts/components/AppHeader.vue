<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";

defineEmits<{
    toggleNavbar: [];
}>();
</script>

<template>
    <header
        class="fixed top-0 left-0 z-20 w-full h-14 bg-white flex flex-row border-b border-b-slate-200"
    >
        <Link href="/">
            <img class="max-h-14 px-4 py-1" src="/images/logo.webp" />
        </Link>
        <h1 class="hidden md:flex md:grow items-center justify-center">
            App Template
        </h1>
        <div
            class="relative grow md:grow-0 flex items-center justify-end gap-2 me-2"
        >
            <BaseButton
                class="lg:hidden"
                icon="bars"
                variant="light"
                @click="$emit('toggleNavbar')"
            />
        </div>
    </header>
</template>
