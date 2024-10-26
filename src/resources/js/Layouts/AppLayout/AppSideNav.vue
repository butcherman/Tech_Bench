<template>
    <nav id="side-nav" class="sidebar" :class="{ active: active }">
        <ul class="nav">
            <li
                v-for="navItem in app.navbar"
                :key="navItem.name"
                class="nav-item"
            >
                <Link :href="navItem.route" class="nav-link">
                    <fa-icon :icon="navItem.icon" />
                    <span class="menu-title">
                        {{ navItem.name }}
                    </span>
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Store/AppStore";

defineProps<{
    active: boolean;
}>();

const app = useAppStore();
</script>
