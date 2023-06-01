<template>
    <nav id="side-nav" class="sidebar" :class="{ active: navbarActive }">
        <ul class="nav">
            <li class="nav-item" v-for="link in navbar" :key="link.name">
                <Link class="nav-link" :href="link.route">
                    <fa-icon :icon="link.icon" />
                    <span class="menu-title">{{ link.name }}</span>
                </Link>
            </li>
        </ul>
    </nav>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { navbarActive } from "@/State/LayoutState";
import { usePage } from "@inertiajs/vue3";

const page: pageData = usePage();
const navbar = computed(() => page.props.navbar);
</script>
