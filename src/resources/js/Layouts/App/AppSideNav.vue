<script setup lang="ts">
import { computed } from "vue";
import { Menu } from "primevue";
import { useAuthStore } from "@/Stores/AuthStore";

const props = defineProps<{
    navbarHidden: boolean;
}>();

/**
 * On Mobile, determine width of navbar based on if hidden or not.
 */
const hiddenClass = computed<string>(() =>
    props.navbarHidden ? "w-0" : "w-64"
);

const auth = useAuthStore();
</script>

<template>
    <nav
        class="fixed top-14 right-0 lg:left-0 h-full z-50 lg:w-64 overflow-hidden rounded-s-lg lg:rounded-none border-s border-s-slate-200 lg:border-0"
        :class="hiddenClass"
    >
        <Menu
            :model="auth.navbar"
            class="pt-4 border-none! rounded-none! h-full"
        >
            <template #item="{ item }">
                <div class="my-2 ps-2">
                    <Link
                        :href="item.route"
                        class="w-full h-full m-0 py-2 block"
                    >
                        <fa-icon :icon="item.icon" />
                        {{ item.name }}
                    </Link>
                </div>
            </template>
        </Menu>
    </nav>
</template>

<style scoped>
nav {
    transition: width;
    transition-duration: 0.5s;
}
</style>
