<script setup lang="ts">
import MenuList from "@/core/components/MenuList.vue";
import { computed } from "vue";
import { useUserState } from "@/features/user/state/userState";

const emit = defineEmits<{
    "update:open": [boolean];
}>();

const props = defineProps<{
    open: boolean;
}>();

const { navBar } = useUserState();

/**
 * Status of the Nav Menu - opened or closed
 */
const isOpen = computed({
    get: () => props.open,
    set: (value) => emit("update:open", value),
});

/**
 * On Mobile, determine width of navbar based on if hidden or not.
 */
const hiddenClass = computed<string>(() => (isOpen.value ? "w-0" : "w-64"));
</script>

<template>
    <nav
        class="fixed top-14 right-0 lg:left-0 h-full z-30 lg:w-64 overflow-hidden rounded-s-lg lg:rounded-none border-s border-s-slate-200 lg:border-0 transition-[width] transition-900 bg-white"
        :class="hiddenClass"
    >
        <div class="mt-4 ms-4 me-2"><MenuList :menu-list="navBar" /></div>
    </nav>
</template>
