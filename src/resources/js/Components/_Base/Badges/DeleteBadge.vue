<template>
    <div class="inline-flex">
        <BaseBadge
            class="pointer"
            :icon="icon ?? 'trash-alt'"
            :variant="variant ?? 'danger'"
            @click="handleClick"
        />
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
    </div>
</template>

<script setup lang="ts">
import { handleLinkClick } from "@/Composables/links.module";
import { useConfirm } from "primevue";
import ConfirmPopup from "primevue/confirmpopup";
import BaseBadge from "./BaseBadge.vue";

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

/*
|---------------------------------------------------------------------------
| If href prop is populated, treat click as a link.
|---------------------------------------------------------------------------
*/
const confirm = useConfirm();
const handleClick = (event: MouseEvent) => {
    if (props.confirm) {
        confirm.require({
            target: event.currentTarget as HTMLElement,
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
</script>
