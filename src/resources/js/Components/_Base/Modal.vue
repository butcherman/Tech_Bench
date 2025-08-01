<script setup lang="ts">
import { computed, readonly, ref } from "vue";

const emit = defineEmits<{
    hide: [];
    hidden: [];
    show: [];
    shown: [];
    "hide-prevented": [];
}>();

const props = defineProps<{
    hideBackdrop?: boolean;
    hideClose?: boolean;
    position?: "top" | "center" | "bottom";
    preventOutsideClick?: boolean;
    title?: string;
    size?: "medium" | "large" | "x-large";
}>();

/**
 * Current visual state of the Modal
 */
const modalOpen = ref<boolean>(false);

/**
 * Where to align the Modal (top, center, bottom)
 */
const modalPosition = computed<string>(() => {
    switch (props.position) {
        case "top":
            return "items-start mt-3";
        case "center":
            return "items-center";
        case "bottom":
            return "items-end";
        default:
            return "items-center";
    }
});

/**
 * Size of the Modal (large, x-large)
 */
const modalSize = computed(() => {
    switch (props.size) {
        case "medium":
            return "w-1/2";
        case "large":
            return "w-3/4";
        case "x-large":
            return "w-full";
        default:
            return "";
    }
});

/**
 * How to handle a click outside of the Modal box
 */
const backdropClicked = (): void => {
    if (!props.preventOutsideClick) {
        hide();
    } else {
        emit("hide-prevented");
    }
};

/**
 * Open the Modal
 */
const show = (): void => {
    emit("show");
    modalOpen.value = true;
};

/**
 * Close the Modal
 */
const hide = (): void => {
    emit("hide");
    modalOpen.value = false;
};

defineExpose({ show, hide, isOpen: readonly(modalOpen) });
</script>

<template>
    <Teleport to="body">
        <Transition
            @after-enter="$emit('shown')"
            @after-leave="$emit('hidden')"
        >
            <div v-show="modalOpen" class="relative z-50">
                <div
                    v-if="!hideBackdrop"
                    class="fixed inset-0 bg-gray-500/75"
                />
                <div
                    class="fixed inset-0 z-50 w-screen overflow-y-auto"
                    @click="backdropClicked"
                >
                    <div
                        class="flex min-h-full justify-center"
                        :class="modalPosition"
                    >
                        <div
                            class="bg-white min-w-96 max-w-4xl min-h-32 rounded-lg p-5 flex flex-col relative"
                            :class="modalSize"
                            @click.stop
                        >
                            <div
                                v-if="!hideClose"
                                class="absolute top-2 right-4 text-muted pointer"
                            >
                                <button class="pointer" @click.stop="hide">
                                    <fa-icon icon="close" />
                                </button>
                            </div>
                            <div
                                class="mb-3"
                                :class="{ 'border-b': $slots.header || title }"
                            >
                                <slot name="header">
                                    <h5 class="text-muted">{{ title }}</h5>
                                </slot>
                            </div>
                            <div class="grow overflow-auto">
                                <slot />
                            </div>
                            <div
                                v-if="$slots.footer"
                                class="border-t flex flex-row-reverse pt-2 mt-3"
                            >
                                <slot name="footer"></slot>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
