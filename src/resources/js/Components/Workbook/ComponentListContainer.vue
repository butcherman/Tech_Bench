<script setup lang="ts">
import GridWrapper from "./GridWrapper.vue";

defineProps<{
    componentList: workbookElement[];
}>();
</script>

<template>
    <div>
        <template v-for="element in componentList">
            <GridWrapper
                v-if="element.type === 'grid-wrapper'"
                :grid-wrapper="element"
            />
            <div v-else style="border: 1px solid red">
                {{ element }}
            </div>
        </template>
    </div>
</template>
