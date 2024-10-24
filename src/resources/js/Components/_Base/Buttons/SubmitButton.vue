<template>
    <button
        type="submit"
        class="btn w-100"
        :class="variant"
        :disabled="submitted"
    >
        <slot>
            <span v-if="submitted" class="spinner-border spinner-border-sm" />
            {{ text ? text : "Submit" }}
        </slot>
    </button>
</template>

<script setup lang="ts">
import { computed } from "vue";
const props = defineProps<{
    text?: string;
    submitted?: boolean;
    btnVariant?: "primary" | "info" | "success" | "danger" | "warning";
}>();

const variant = computed(() =>
    props.btnVariant ? `btn-${props.btnVariant}` : "btn-primary"
);
</script>
