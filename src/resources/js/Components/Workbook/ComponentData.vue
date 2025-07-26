<script setup lang="ts">
import { computed, defineAsyncComponent } from "vue";

const props = defineProps<{
    element: workbookElement;
}>();

const component = computed(() => {
    if (props.element.component) {
        return defineAsyncComponent(
            () => import(`../../Forms/_Base/${props.element.component}.vue`)
        );
    }

    return props.element.tag;
});
</script>

<template>
    <component
        :is="component"
        :id="element.index"
        :name="element.index"
        :class="element.class"
        v-bind="element.props"
        v-html="element.text"
    />
</template>
