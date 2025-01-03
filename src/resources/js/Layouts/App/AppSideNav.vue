<template>
    <div
        id="app-side-nav"
        class="w-64 z-50 h-full overflow-hidden -sm:rounded-lg -md:absolute -md:right-0 bg-blue-500"
        :class="{ '-md:w-0 md:block': navbarHidden }"
        style="border: 1px solid red"
    >
        navbar stuff
    </div>
</template>

<script setup lang="ts">
defineProps<{
    navbarHidden: boolean | null;
}>();
</script>

<style scoped lang="css">
#app-side-nav {
    transition: all;
    transition-duration: 0.5s;
}
</style>
