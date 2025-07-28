<script setup lang="ts">
import { computed, defineAsyncComponent, inject } from "vue";

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

const triggerSave: (index: string) => void = inject("triggerSave", (index) =>
    alert("Inject opteration failed for " + index)
);
</script>

<template>
    <component
        :is="component"
        :id="element.index"
        :name="element.index"
        :class="element.class"
        v-bind="element.props"
        v-html="element.text"
        @blur="triggerSave(element.index)"
    />
</template>
