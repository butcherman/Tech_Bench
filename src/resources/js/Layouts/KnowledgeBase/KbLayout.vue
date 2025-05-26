<script setup lang="ts">
import AppFooter from "../App/AppFooter.vue";
import KbHeader from "./KbHeader.vue";
</script>

<template>
    <div id="kb-layout-wrapper" class="flex flex-col bg-gray-200">
        <KbHeader />
        <section id="kb-content" class="mt-14 p-3 grow">
            <slot />
        </section>
        <AppFooter class="bg-slate-100" />
    </div>
</template>

<style scoped lang="postcss">
#kb-layout-wrapper {
    min-height: 100vh;
    width: 100%;
}
</style>
