<script setup lang="ts">
import { Menu } from "primevue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

defineProps<{
    navbarHidden: boolean | null;
}>();

const navbar = computed<navbarItem[]>(() => usePage<pageProps>().props.navbar);
</script>

<template>
    <nav
        :class="{ 'navbar-hidden': navbarHidden }"
        class="h-full w-64 z-20 fixed top-14 -md:right-0 overflow-hidden border-e -md:border -md:rounded-lg md:rounded-none"
    >
        <Menu :model="navbar" id="app-navbar-menu" class="h-full w-full p-4">
            <template #item="{ item }">
                <div class="my-2 mx-2">
                    <Link :href="item.route">
                        <fa-icon :icon="item.icon" />
                        {{ item.name }}
                    </Link>
                </div>
            </template>
        </Menu>
    </nav>
</template>

<style lang="postcss" scoped>
@reference '../../../css/app.css'

nav {
    transition: width;
    transition-duration: 0.5s;
}

.navbar-hidden {
    @apply -md:w-0;
}
</style>
