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
const saveFieldValue = inject("saveFieldValue", (index: string) =>
    alert("Fatal Error - Injection Failed"),
);
</script>

<template>
    <component
        :is="nodeType"
        :id="index"
        :name="index"
        v-bind="attrs"
        @change="saveFieldValue(index)"
    />
</template>
