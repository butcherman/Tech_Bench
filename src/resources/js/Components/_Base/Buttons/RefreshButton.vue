<template>
    <Button
        class="px-2 py-2 rounded-full"
        :class="variantClass"
        :rounded="pill"
        :raised="!flat"
        v-tooltip="'Refresh'"
        @click="handleClick"
    >
        <slot>
            <fa-icon icon="fa-rotate" :spin="isLoading" />
        </slot>
    </Button>
</template>

<script setup lang="ts">
import { Button } from "primevue";
import { computed, ref } from "vue";
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
    console.log("clicked");
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

/*
|---------------------------------------------------------------------------
| Background Color
|---------------------------------------------------------------------------
*/
const variantClass = computed<string>(() => {
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
            return "bg-neutral-100";
    }
});
</script>
