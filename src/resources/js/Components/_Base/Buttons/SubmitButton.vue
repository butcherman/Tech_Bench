<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    text?: string;
    submitted?: boolean;
    btnVariant?: elementVariant;
}>();

const variant = computed<string>(() =>
    props.btnVariant ? `btn-${props.btnVariant}` : "btn-primary"
);
</script>

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
