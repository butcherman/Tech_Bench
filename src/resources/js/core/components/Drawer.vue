<script setup lang="ts">
import { computed } from "vue";
import BaseBadge from "./badges/BaseBadge.vue";
import gsap from "gsap";

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

/*
|-------------------------------------------------------------------------------
| Animations
|-------------------------------------------------------------------------------
*/
const onEnter = (el: Element, done: () => void): void => {
    if (props.position === "left" || props.position === "right") {
        gsap.from(el, {
            width: 0,
            ease: "back.out",
            duration: 0.5,
            onComplete: done,
        });
        return;
    }

    gsap.from(el, {
        height: 0,
        ease: "back.out",
        duration: 0.5,
        onComplete: done,
    });
};

const onLeave = (el: Element, done: () => void) => {
    if (props.position === "left" || props.position === "right") {
        gsap.to(el, {
            width: 0,
            ease: "back.out",
            duration: 0.5,
            onComplete: done,
        });
        return;
    }

    gsap.to(el, {
        height: 0,
        ease: "back.in",
        duration: 0.5,
        onComplete: done,
    });
};
</script>

<template>
    <Teleport to="body">
        <Transition :css="false" @enter="onEnter" @leave="onLeave">
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
    </Teleport>
</template>
