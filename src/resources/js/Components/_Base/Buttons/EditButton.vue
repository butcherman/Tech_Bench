<template>
    <button
        type="button"
        class="btn btn-warning mx-1"
        :class="{ 'rounded-5': pill, 'btn-sm': small }"
        @click="onBtnClick"
    >
        <fa-icon icon="edit" class="me-1" />
        <slot>{{ text || "Edit" }}</slot>
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
