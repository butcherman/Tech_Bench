<script setup lang="ts">
import BaseButton from "../buttons/BaseButton.vue";
import BaseBadge from "./BaseBadge.vue";
import { computed, ref } from "vue";

const emit = defineEmits<{
    yesClicked: [];
    noClicked: [];
}>();

const props = defineProps<{
    circle?: boolean;
    confirm?: boolean;
    href?: string;
    icon?: string;
    pointer?: boolean;
    text?: string;
    variant?: VariantType;
}>();

const showConfirm = ref(false);
const badgeIcon = computed(() => props.icon ?? "trash-alt");
const showPointer = computed(() => props.pointer || props.confirm);

const onBadgeclick = () => {
    if (props.confirm) {
        showConfirm.value = true;
    }
};

const onConfirmClick = (res: "yes" | "no") => {
    if (res === "yes") {
        emit("yesClicked");
    }

    if (res === "no") {
        emit("noClicked");
    }

    showConfirm.value = false;
};
</script>

<template>
    <div class="inline-flex relative">
        <BaseBadge
            v-bind="props"
            :pointer="showPointer"
            :variant="variant ?? 'danger'"
            @click="onBadgeclick"
        >
            <slot>
                <fa-icon v-if="badgeIcon" :icon="badgeIcon" />
                <span v-if="text">{{ text }}</span>
            </slot>
        </BaseBadge>
        <div
            v-if="showConfirm"
            class="confirm-dialog absolute top-full right-0 mt-2 bg-white border border-slate-300 rounded-lg w-35 z-50"
            v-on-click-outside="() => (showConfirm = false)"
        >
            <div class="text-sm flex flex-row items-center gap-1 p-2">
                <fa-icon icon="exclamation-circle" class="text-danger" />
                Are You Sure?
            </div>
            <div class="flex flex-row justify-center gap-2 pb-2 text-sm">
                <BaseButton
                    icon="check"
                    text="Yes"
                    size="sm"
                    variant="danger"
                    @click="onConfirmClick('yes')"
                />
                <BaseButton
                    icon="xmark"
                    text="No"
                    size="sm"
                    variant="success"
                    @click="onConfirmClick('no')"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
.confirm-dialog::before,
.confirm-dialog::after {
    content: "";
    position: absolute;
    right: 12px;
    transform: translateX(50%);
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
}

/* Border triangle */
.confirm-dialog::before {
    bottom: 100%;
    border-bottom: 8px solid #cbd5e1; /* slate-300 */
}

/* White inner triangle */
.confirm-dialog::after {
    bottom: calc(100% - 1px);
    border-left-width: 7px;
    border-right-width: 7px;
    border-bottom: 7px solid white;
}
</style>
