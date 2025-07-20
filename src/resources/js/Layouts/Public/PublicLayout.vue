<script setup lang="ts">
import AppFooter from "../App/AppFooter.vue";
import FlashAlert from "../_Shared/FlashAlert.vue";
import PublicHeader from "./PublicHeader.vue";
</script>

<template>
    <div id="kb-layout-wrapper" class="flex flex-col bg-gray-200 h-screen">
        <PublicHeader />
        <FlashAlert />
        <section class="grow flex flex-col mt-14 p-3">
            <slot />
        </section>
        <AppFooter class="bg-slate-100" />
    </div>
</template>
