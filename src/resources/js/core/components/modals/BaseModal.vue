<script setup lang="ts">
import { computed, watch } from "vue";

const emit = defineEmits<{
    "update:open": [boolean];
    hidePrevented: [];
    hide: [];
    hidden: [];
    show: [];
    shown: [];
}>();

const props = defineProps<{
    open: boolean;

    hideBackdrop?: boolean;
    hideClose?: boolean;
    position?: "top" | "center" | "bottom";
    preventOutsideClick?: boolean;
    size?: componentSize;
    title?: string;
}>();

watch(props, (newProps) => {
    if (newProps.open) {
        emit("show");
        return;
    }

    if (!newProps.open) {
        emit("hide");
        return;
    }
});

/**
 * Modal visual state
 */
const isOpen = computed({
    get: () => props.open,
    set: (value) => emit("update:open", value),
});

/**
 * Determine if the modal should close when the backdrop is clicked
 */
const onBackgroundClicked = () => {
    if (props.preventOutsideClick) {
        emit("hidePrevented");
        return;
    }

    isOpen.value = false;
};

/**
 * Determine the size of the Modal
 */
const modalSize = computed<string>(() => {
    switch (props.size) {
        case "large":
            return "w-full";
        case "small":
            return "w-1/2";
        case "normal":
            return "w-3/4";
        default:
            return "";
    }
});

/**
 * Determine the position of the Modal
 */
const modalPosition = computed<string>(() => {
    switch (props.position) {
        case "top":
            return "items-start";
        case "bottom":
            return "items-end";
        case "center":
        default:
            return "items-center";
    }
});
</script>

<template>
    <Teleport to="body">
        <Transition
            name="modal"
            @after-enter="$emit('shown')"
            @after-leave="$emit('hidden')"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50 w-screen overflow-y-auto flex justify-center"
                :class="[modalPosition, { 'bg-gray-500/75': !hideBackdrop }]"
            >
                <div
                    class="bg-white min-w-96 m-4 min-h-32 rounded-lg p-5 flex flex-col relative"
                    :class="modalSize"
                    v-on-click-outside="onBackgroundClicked"
                >
                    <div
                        v-if="!hideClose"
                        class="absolute top-2 right-4 text-muted pointer"
                    >
                        <button class="pointer" @click="isOpen = false">
                            <fa-icon icon="close" />
                        </button>
                    </div>
                    <div
                        class="mb-3 border-slate-300"
                        :class="{ 'border-b': $slots.header || title }"
                    >
                        <slot name="header">
                            <h5 class="text-muted">{{ title }}</h5>
                        </slot>
                    </div>
                    <div class="grow overflow-auto">
                        <slot />
                    </div>
                    <div v-if="$slots.footer" class="border-t pt-2 mt-3">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.5s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
