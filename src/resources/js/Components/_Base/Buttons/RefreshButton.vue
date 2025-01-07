<template>
    <BaseButton
        variant="light"
        :pill="pill"
        v-tooltip="'Refresh'"
        :flat="flat"
        @click="handleClick"
    >
        <slot>
            <fa-icon icon="fa-rotate" :spin="isLoading" />
        </slot>
    </BaseButton>
</template>

<script setup lang="ts">
import BaseButton from "./BaseButton.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const emit = defineEmits(["loading-start", "loading-complete"]);
const props = defineProps<{
    only?: string[];
    flat?: boolean;
    pill?: boolean;
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

const isLoading = ref<boolean>(false);
const handleClick = (): void => {
    isLoading.value = true;
    emit("loading-start");

    let base = ["flash"];

    router.reload({
        only: base.concat(props.only ?? []),
        onFinish: () => {
            isLoading.value = false;
            emit("loading-complete");
        },
    });
};
</script>
