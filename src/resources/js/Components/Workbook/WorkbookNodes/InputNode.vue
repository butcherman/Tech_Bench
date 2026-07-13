<script setup lang="ts">
import { computed, defineAsyncComponent, inject, useAttrs } from "vue";

const props = defineProps<{
    component: string;
    index: string;
}>();

// Additional props for component
const attrs = useAttrs();

/**
 * Download the Async Component
 */
const nodeType = computed(() => {
    if (props.component) {
        return defineAsyncComponent(
            () => import(`../../../Forms/_Base/${props.component}.vue`),
        );
    }
});

/**
 * Inject save method
 */
const saveFieldValue = inject<((index: string) => void) | null>(
    "saveFieldValue",
    null,
);

const saveField = () => {
    if (saveFieldValue) {
        saveFieldValue(props.index);
    }
};
</script>

<template>
    <component
        v-bind="attrs"
        :is="nodeType"
        :id="index"
        :name="index"
        @change="saveField"
    />
</template>
