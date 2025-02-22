<script setup lang="ts">
import BaseButton from "./BaseButton.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const emit = defineEmits<{
    "loading-start": [];
    "loading-complete": [];
}>();

const props = defineProps<{
    flat?: boolean;
    only?: string[];
    pill?: boolean;
    variant?: elementVariant;
}>();

const isLoading = ref<boolean>(false);

/*
|-------------------------------------------------------------------------------
| Reload the page
|-------------------------------------------------------------------------------
*/
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

<template>
    <BaseButton
        size="small"
        variant="light"
        :flat="flat"
        :pill="pill"
        v-tooltip="'Refresh'"
        @click="handleClick"
    >
        <slot>
            <fa-icon icon="fa-rotate" :spin="isLoading" />
        </slot>
    </BaseButton>
</template>
