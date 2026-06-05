<script setup lang="ts">
import NodeOptions from "../NodeOptions.vue";
import { computed, defineAsyncComponent, useAttrs } from "vue";

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
            () => import(`../../../../Forms/_Base/${props.component}.vue`),
        );
    }
});
</script>

<template>
    <div class="relative group/input">
        <NodeOptions
            class="hidden group-hover/input:flex"
            :can-edit="true"
            :node-index="index"
        />
        <component :is="nodeType" :id="index" :name="index" v-bind="attrs" />
    </div>
</template>
