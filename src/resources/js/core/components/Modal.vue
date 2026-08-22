<script setup lang="ts">
import { computed, ref, watch } from "vue";

const emit = defineEmits<{
    "update:show": [boolean];
    hidePrevented: [];
    hide: [];
    hidden: [];
    show: [];
    shown: [];
}>();

const props = defineProps<{
    show: boolean;

    hideBackdrop?: boolean;
    hideClose?: boolean;
    position?: "top" | "center" | "bottom";
    preventOutsideClick?: boolean;
    size?: ComponentSize;
    title?: string;
}>();

watch(
    () => props.show,
    (show) => {
        if (show) emit("show");
        if (!show) emit("hide");
    },
);

/**
 * Modal visual state
 */
const isOpen = computed({
    get: () => props.show,
    set: (value) => emit("update:show", value),
});

/**
 * Determine if the modal should close when the backdrop is clicked
 */
const onBackgroundClicked = () => {
    if (props.preventOutsideClick) {
        emit("hidePrevented");
        attentionRequired.value = true;

        setTimeout(() => (attentionRequired.value = false), 1000);
        return;
    }

    isOpen.value = false;
};

/**
 * Determine the size of the Modal
 */
const modalSize = computed<string>(() => {
    return {
        lg: "w-full",
        sm: "w-1/2",
        md: "w-3/4",
    }[props.size ?? "md"];
});

/**
 * Determine the position of the Modal
 */
const modalPosition = computed<string>(() => {
    return {
        top: "items-start",
        bottom: "items-end",
        center: "items-center",
    }[props.position ?? "center"];
});

/**
 * Attention Status of the Modal
 */
const attentionRequired = ref<boolean>(false);
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
                class="tb-modal fixed inset-0 z-50 w-screen overflow-y-auto flex justify-center"
                :class="[modalPosition, { 'bg-gray-500/75': !hideBackdrop }]"
            >
                <div
                    class="tb-modal-body bg-white min-w-96 m-4 min-h-32 rounded-lg p-5 flex flex-col relative border border-slate-300"
                    :class="[modalSize, { attention: attentionRequired }]"
                    v-on-click-outside="onBackgroundClicked"
                >
                    <div
                        v-if="!hideClose"
                        class="absolute top-2 right-4 text-muted pointer"
                    >
                        <button
                            class="pointer hide-button"
                            @click="isOpen = false"
                        >
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

.attention {
    animation: modal-attention 0.3s ease;
}

@keyframes modal-attention {
    0% {
        transform: translateX(0);
    }
    20% {
        transform: translateX(-8px);
    }
    40% {
        transform: translateX(8px);
    }
    60% {
        transform: translateX(-6px);
    }
    80% {
        transform: translateX(6px);
    }
    100% {
        transform: translateX(0);
    }
}
</style>
