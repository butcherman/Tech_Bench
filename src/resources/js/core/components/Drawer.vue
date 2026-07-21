<script setup lang="ts">
import { computed } from "vue";
import BaseBadge from "./badges/BaseBadge.vue";

const emit = defineEmits(["update:modelValue"]);

const props = defineProps<{
    modelValue: boolean;
    position?: "left" | "right" | "bottom" | "top";
    title?: string;
}>();

const positionClass = computed(() => {
    switch (props.position) {
        case "left":
            return "top-0 left-0 border-e h-screen min-w-96";
        case "right":
            return "top-0 right-0 border-s h-screen min-w-96";
        case "top":
            return "top-0 left-0 right-0 border-b w-full min-h-96";
        default:
            return "bottom-0 left-0 right-0 border-t w-full min-h-96";
    }
});

/**
 * Status of the Drawer - opened or closed
 */
const isOpen = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});
</script>

<template>
    <Teleport to="body">
        <div v-if="isOpen" id="drawer-wrapper">
            <Transition name="drawer-backdrop" appear>
                <div
                    v-if="isOpen"
                    class="fixed inset-0 bg-gray-500/75 z-40"
                    @click="isOpen = false"
                />
            </Transition>
            <Transition :name="`drawer-${position ?? 'bottom'}`" appear>
                <div
                    v-if="isOpen"
                    id="drawer"
                    class="fixed p-4 overflow-y-auto bg-white border-slate-300 z-50"
                    :class="positionClass"
                    tabindex="-1"
                >
                    <div
                        class="border-b border-slate-200 pb-4 mb-5 flex flex-row-reverse"
                    >
                        <BaseBadge
                            icon="xmark"
                            variant="light"
                            class="text-white pointer"
                            circle
                            @click="isOpen = false"
                        />
                        <h5 class="grow text-muted">
                            {{ title }}
                        </h5>
                    </div>
                    <div>
                        <slot />
                    </div>
                </div>
            </Transition>
        </div>
    </Teleport>
</template>

<style scoped>
/**
* Backdrop
*/
.drawer-backdrop-enter-active,
.drawer-backdrop-leave-active {
    transition: opacity 250ms ease;
}

.drawer-backdrop-enter-from,
.drawer-backdrop-leave-to {
    opacity: 0;
}

/**
* Right Drawer
*/
.drawer-right-enter-active,
.drawer-right-leave-active {
    transition:
        transform 500ms ease,
        opacity 500ms ease;
}

.drawer-right-enter-from,
.drawer-right-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

/**
* Left Drawer
*/
.drawer-left-enter-from,
.drawer-left-leave-to {
    transform: translateX(-100%);
    opacity: 0;
}

.drawer-left-enter-active,
.drawer-left-leave-active {
    transition:
        transform 500ms ease,
        opacity 500ms ease;
}

/**
* Bottom Drawer
*/
.drawer-bottom-enter-from,
.drawer-bottom-leave-to {
    transform: translateY(100%);
    opacity: 0;
}

.drawer-bottom-enter-active,
.drawer-bottom-leave-active {
    transition:
        transform 500ms ease,
        opacity 500ms ease;
}

/**
* Top Drawer
*/
.drawer-top-enter-from,
.drawer-top-leave-to {
    transform: translateY(-100%);
    opacity: 0;
}

.drawer-top-enter-active,
.drawer-top-leave-active {
    transition:
        transform 500ms ease,
        opacity 500ms ease;
}

/**
* Queue drawer after backdrop loads
*/
.drawer-backdrop-enter-active {
    transition: opacity 180ms ease;
}

.drawer-right-enter-active,
.drawer-left-enter-active,
.drawer-top-enter-active,
.drawer-bottom-enter-active {
    transition:
        transform 280ms cubic-bezier(0.22, 1, 0.36, 1) 120ms,
        opacity 280ms ease 120ms;
}
</style>
