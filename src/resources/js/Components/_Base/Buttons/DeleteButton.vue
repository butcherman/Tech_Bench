<script setup lang="ts">
import BaseButton from "./BaseButton.vue";
import ConfirmPopup from "primevue/confirmpopup";
import { useConfirm } from "primevue";
import { handleLinkClick } from "@/Composables/links.module";

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
    <div class="inline-flex">
        <BaseButton
            class="w-full"
            :flat="flat"
            :pill="pill"
            :variant="variant ?? 'danger'"
            @click="handleClick"
        >
            <slot>
                <fa-icon :icon="icon ?? 'trash-alt'" />
                {{ text ?? "Delete" }}
            </slot>
        </BaseButton>
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
