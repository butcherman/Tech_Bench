<script setup lang="ts">
import Collapse from "@/core/components/Collapse.vue";
import ExpandBadge from "@/core/components/badges/ExpandBadge.vue";
import MenuList from "@/core/components/MenuList.vue";
import { computed, ref } from "vue";
import { useDemoNavbar } from "@/features/composables/demoNavbar";

const emit = defineEmits(["update:open"]);

const props = defineProps<{
    open: boolean;
}>();

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

const navBar = useDemoNavbar;

const basic = ref(false);
const components = ref(false);
const directives = ref(false);
const forms = ref(false);
</script>

<template>
    <nav
        class="fixed top-14 right-0 lg:left-0 h-full z-30 lg:w-64 overflow-hidden rounded-s-lg lg:rounded-none border-s border-s-slate-200 lg:border-0 transition-[width] transition-900 bg-white"
        :class="hiddenClass"
    >
        <div class="mt-4 ms-4 me-2">
            <h5 class="pointer" @click="basic = !basic">
                <ExpandBadge :expanded="basic" />
                Basic Components
            </h5>
            <Collapse :show="basic">
                <MenuList :menu-list="navBar.basicComponents" />
            </Collapse>
            <h5 class="pointer" @click="components = !components">
                <ExpandBadge :expanded="components" />
                Data Components
            </h5>
            <Collapse :show="components">
                <MenuList :menu-list="navBar.dataComponents" />
            </Collapse>
            <h5 class="pointer" @click="directives = !directives">
                <ExpandBadge :expanded="directives" />
                Directives
            </h5>
            <Collapse :show="directives">
                <MenuList :menu-list="navBar.directives" />
            </Collapse>
            <h5 class="pointer" @click="forms = !forms">
                <ExpandBadge :expanded="forms" />
                Form Components
            </h5>
            <Collapse :show="forms">
                <MenuList :menu-list="navBar.forms" />
            </Collapse>
        </div>
    </nav>
</template>
