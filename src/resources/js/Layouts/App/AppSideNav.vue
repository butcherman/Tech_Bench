<template>
    <nav :class="{ 'navbar-hidden': navbarHidden }">
        <Menu :model="app.navbar" id="app-navbar-menu">
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

<script setup lang="ts">
import { Menu } from "primevue";
import { useAppStore } from "@/Stores/AppStore";

defineProps<{
    navbarHidden: boolean | null;
}>();

const app = useAppStore();
</script>
