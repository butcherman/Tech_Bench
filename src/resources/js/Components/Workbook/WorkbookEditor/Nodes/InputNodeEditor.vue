<script setup lang="ts">
import InputNode from "../../WorkbookNodes/InputNode.vue";
import NodeOptions from "../NodeOptions.vue";
import { useAttrs } from "vue";

const props = defineProps<{
    component: string;
    index: string;
}>();

// Additional props for component
const attrs = useAttrs();
</script>

<template>
    <div class="relative group/input">
        <NodeOptions
            class="hidden group-hover/input:flex"
            :can-edit="true"
            :node-index="index"
        />
        <InputNode :component="component" :index="index" v-bind="attrs" />
    </div>
</template>
