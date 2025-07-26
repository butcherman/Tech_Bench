<script setup lang="ts">
import ComponentOptions from "./ComponentOptions.vue";
import { computed, defineAsyncComponent } from "vue";

const props = defineProps<{
    element: workbookElement;
    container: workbookElement[];
}>();

const component = computed(() => {
    if (props.element.component) {
        return defineAsyncComponent(
            () => import(`../../../Forms/_Base/${props.element.component}.vue`)
        );
    }

    return props.element.tag;
});
</script>

<template>
    <div
        class="border border-dashed border-slate-200 hover:border-green-300 hover:border-solid p-1 my-1 relative group"
    >
        <ComponentOptions
            :component="element"
            :container="container"
            class="hidden group-hover:flex"
        />
        <component
            :is="component"
            :id="element.index"
            :name="element.index"
            :class="element.class"
            v-bind="element.props"
            v-html="element.text"
        />
    </div>
</template>
