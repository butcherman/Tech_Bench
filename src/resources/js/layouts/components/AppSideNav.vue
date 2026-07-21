<script setup lang="ts">
import { computed } from "vue";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps<{
    modelValue: boolean;
}>();

/**
 * Status of the Nav Menu - opened or closed
 */
const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
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
        nav menu
        <!-- <Menu
            :model="auth.navbar"
            class="pt-4 border-none! rounded-none! h-full"
        >
            <template #item="{ item }">
                <div class="ps-2">
                    <Link
                        :href="item.route"
                        class="w-full h-full m-0 py-2 block"
                    >
                        <fa-icon :icon="item.icon" />
                        {{ item.name }}
                    </Link>
                </div>
            </template>
        </Menu> -->
    </nav>
</template>
