<script setup lang="ts">
import ElementWrapper from "./ElementWrapper.vue";

defineProps<{
    gridRow: workbookEntry;
}>();
</script>

<template>
    <div :class="gridRow.class">
        <div v-for="col in gridRow.container" :class="col.class">
            <h4
                v-if="!col.container?.length"
                class="text-center text-muted opacity-50"
            >
                Empty Container
            </h4>
            <template v-for="element in col.container">
                <ElementWrapper :component="element" />
            </template>
        </div>
    </div>
</template>
