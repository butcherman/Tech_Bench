<template>
    <span style="display: inline-flex">
        <Badge
            class="rounded-full pointer"
            :class="variantClass"
            @click="handleClick"
        >
            <fa-icon :icon="icon ?? 'trash-alt'" />
        </Badge>
        <ConfirmPopup>
            <template #icon>
                <fa-icon icon="exclamation-circle" class="text-danger" />
            </template>
            <template #accepticon>
                <fa-icon icon="check" class="text-danger" />
            </template>
            <template #rejecticon>
                <fa-icon icon="xmark" />
            </template>
        </ConfirmPopup>
    </span>
</template>

<script setup lang="ts">
import { Badge } from "primevue";
import { computed } from "vue";
import { handleLinkClick } from "@/Composables/links.module";
import { useConfirm } from "primevue";
import ConfirmPopup from "primevue/confirmpopup";

const emit = defineEmits(["accepted", "rejected"]);
const props = defineProps<{
    confirm?: boolean;
    confirmMsg?: string;
    acceptText?: string;
    rejectText?: string;
    href?: string;
    icon?: string;
    variant?:
        | "danger"
        | "dark"
        | "error"
        | "help"
        | "info"
        | "light"
        | "primary"
        | "secondary"
        | "success"
        | "warning";
}>();

const confirm = useConfirm();

/*
|---------------------------------------------------------------------------
| If href prop is populated, treat click as a link.
|---------------------------------------------------------------------------
*/
const handleClick = (event: MouseEvent) => {
    if (props.confirm) {
        confirm.require({
            message: props.confirmMsg ?? "Are You Sure?",
            acceptLabel: props.acceptText ?? "Yes",
            acceptClass: "border px-2",
            rejectLabel: props.rejectText ?? "No",
            rejectClass: "border px-2",
            accept: () => {
                emit("accepted");
                if (props.href) {
                    handleLinkClick(event, props.href);
                }
            },
            reject: () => {
                emit("rejected");
            },
        });

        return;
    }

    if (props.href) {
        handleLinkClick(event, props.href);
    }
};

/*
|---------------------------------------------------------------------------
| Background Color
|---------------------------------------------------------------------------
*/
const variantClass = computed(() => {
    switch (props.variant) {
        case "danger":
            return "bg-rose-600 text-white";
        case "dark":
            return "bg-gray-900 text-white";
        case "error":
            return "bg-red-500 text-white";
        case "help":
            return "bg-violet-600 text-white";
        case "info":
            return "bg-blue-300";
        case "light":
            return "bg-neutral-300";
        case "primary":
            return "bg-blue-500 text-white";
        case "secondary":
            return "bg-blue-300";
        case "success":
            return "bg-green-500 text-white";
        case "warning":
            return "bg-yellow-400";
        default:
            return "bg-rose-500 text-white";
    }
});
</script>
