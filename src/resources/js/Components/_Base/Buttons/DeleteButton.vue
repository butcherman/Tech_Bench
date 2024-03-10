<template>
    <button
        type="button"
        class="btn btn-danger mx-1"
        :class="{ 'btn-pill': pill, 'btn-sm': small }"
        @click="onBtnClick"
    >
        <fa-icon icon="fa-trash-can" class="me-1" />
        <slot>{{ text || "Delete" }}</slot>
    </button>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    pill?: boolean;
    small?: boolean;
    href?: string;
    text?: string;
}>();

const onBtnClick = () => {
    if (props.href) {
        router.get(props.href);
    }
};
</script>
