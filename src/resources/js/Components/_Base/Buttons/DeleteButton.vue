<template>
    <v-btn
        prepend-icon="trash-alt"
        base-color="danger"
        :size="size ?? 'default'"
        :rounded="pill"
        @click="handleClick"
    >
        {{ text ?? "Delete" }}
    </v-btn>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";

const emit = defineEmits(["click"]);
const props = defineProps<{
    pill?: boolean;
    size?: "x-small" | "small" | "default" | "large" | "x-large";
    text?: string;
    href?: string;
}>();

const handleClick = (event: MouseEvent) => {
    emit("click");

    if (props.href) {
        let location = props.href;

        if (event.ctrlKey) {
            window.open(location);
        } else {
            router.get(location);
        }
    }
};
</script>
