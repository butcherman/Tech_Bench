<script setup lang="ts">
import BaseBadge from "./BaseBadge.vue";
import ConfirmPopup from "primevue/confirmpopup";
import { handleLinkClick } from "@/Composables/links.module";
import { useConfirm } from "primevue";

const emit = defineEmits<{
    accepted: [];
    rejected: [];
}>();

const props = defineProps<{
    acceptText?: string;
    confirm?: boolean;
    confirmMsg?: string;
    href?: string;
    icon?: string;
    method?: "delete" | "get" | "post" | "put";
    preserveScroll?: boolean;
    rejectText?: string;
    variant?: elementVariant;
}>();

/*
|-------------------------------------------------------------------------------
| If href prop is populated, treat click as a link.
|-------------------------------------------------------------------------------
*/
const confirm = useConfirm();
const handleClick = (event: MouseEvent): void => {
    if (props.confirm) {
        confirm.require({
            acceptClass: "border px-2",
            acceptLabel: props.acceptText ?? "Yes",
            message: props.confirmMsg ?? "Are You Sure?",
            rejectClass: "border px-2",
            rejectLabel: props.rejectText ?? "No",
            target: event.currentTarget as HTMLElement,
            accept: () => {
                emit("accepted");
                if (props.href) {
                    handleLinkClick(
                        event,
                        props.href,
                        props.method ?? "delete"
                    );
                }
            },
            reject: () => {
                emit("rejected");
            },
        });

        return;
    }

    if (props.href) {
        handleLinkClick(event, props.href, props.method ?? "delete");
    }
};
</script>

<template>
    <div class="inline-flex">
        <BaseBadge
            class="pointer"
            :icon="icon ?? 'trash-alt'"
            :variant="variant ?? 'danger'"
            @click="handleClick"
        />
        <ConfirmPopup
            :pt="{
                pcRejectButton: {
                    root: 'bg-green-500!',
                },
                pcAcceptButton: {
                    root: 'bg-red-500!',
                },
            }"
        >
            <template #icon>
                <fa-icon icon="exclamation-circle" class="text-danger" />
            </template>
            <template #accepticon>
                <fa-icon icon="check" class="text-red-800" />
            </template>
            <template #rejecticon>
                <fa-icon icon="xmark" />
            </template>
        </ConfirmPopup>
    </div>
</template>
