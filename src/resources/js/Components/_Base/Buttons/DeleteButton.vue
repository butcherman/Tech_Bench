<script setup lang="ts">
import BaseButton from "./BaseButton.vue";
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
    flat?: boolean;
    href?: string;
    icon?: string;
    method?: "delete" | "get" | "post" | "put";
    pill?: boolean;
    rejectText?: string;
    size?: "small" | "normal" | "large";
    text?: string;
    variant?: elementVariant;
}>();

/*
|-------------------------------------------------------------------------------
| Use a Dialog Box to confirm delete
|-------------------------------------------------------------------------------
*/
const confirm = useConfirm();

const handleClick = (event: MouseEvent) => {
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

    handleLinkClick(event, props.href, props.method ?? "delete");
};
</script>

<template>
    <div class="inline-flex p-0 m-0">
        <BaseButton
            class="w-full"
            :flat="flat"
            :pill="pill"
            :size="size"
            :variant="variant ?? 'danger'"
            @click.prevent="handleClick"
        >
            <slot>
                <fa-icon :icon="icon ?? 'trash-alt'" />
                {{ text ?? "Delete" }}
            </slot>
        </BaseButton>
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
                <fa-icon icon="check" class="text-danger" />
            </template>
            <template #rejecticon>
                <fa-icon icon="xmark" />
            </template>
        </ConfirmPopup>
    </div>
</template>
