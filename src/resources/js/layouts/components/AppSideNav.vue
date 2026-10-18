<script setup lang="ts">
import { computed } from "vue";
import { useUserState } from "@/features/user/state/userState";
import { useAppData } from "@/core/state/appData.js";

const { navBar } = useUserState();
const { appName } = useAppData();

/**
 * Status of the Nav Menu - opened or closed
 */
const isOpen = defineModel();

/**
 * On Mobile, determine width of navbar based on if hidden or not.
 */
const hiddenClass = computed<string>(() => (isOpen.value ? "w-0" : "w-64"));
</script>

<template>
    <nav
        class="fixed top-0 right-0 lg:left-0 h-full z-30 lg:w-64 overflow-hidden rounded-s-lg lg:rounded-none transition-[width] transition-900 bg-sidebar"
        :class="hiddenClass"
    >
        <div
            class="h-14 flex items-center justify-center text-white border-b border-slate-300 bg-sidebar-header"
        >
            <h2 class="items-center justify-center">
                {{ appName }}
            </h2>
        </div>
        <div class="my-2">
            <ul>
                <li
                    v-for="nav in navBar.base"
                    :key="nav.label"
                    class="text-slate-200"
                >
                    <Link :href="nav.route">
                        <h4 class="ps-4 py-4 hover:bg-highlight-nav rounded-lg">
                            <fa-icon :icon="nav.icon" />
                            {{ nav.label }}
                        </h4>
                    </Link>
                </li>
            </ul>
        </div>
        <div v-if="navBar.admin.length" class="border-t border-slate-300">
            <ul class="my-2">
                <li
                    v-for="nav in navBar.admin"
                    :key="nav.label"
                    class="text-slate-200"
                >
                    <Link :href="nav.route">
                        <h4 class="ps-4 py-4 hover:bg-highlight-nav rounded-lg">
                            <fa-icon :icon="nav.icon" />
                            {{ nav.label }}
                        </h4>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>
