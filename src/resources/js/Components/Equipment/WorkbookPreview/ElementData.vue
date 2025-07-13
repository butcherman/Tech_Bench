<script setup lang="ts">
import { computed, defineAsyncComponent } from "vue";

const props = defineProps<{
    elem: workbookElement;
}>();

const theComponent = computed(() => {
    if (props.elem.component) {
        return defineAsyncComponent(
            () => import(`../../../Forms/_Base/${props.elem.component}.vue`)
        );
    }

    return props.elem.tag;
});
</script>

<template>
    <div>
        <component
            :is="theComponent"
            :id="elem.index"
            :name="elem.index"
            :class="elem.class"
            v-bind="elem.props"
            v-html="elem.text"
        />
    </div>
</template>
