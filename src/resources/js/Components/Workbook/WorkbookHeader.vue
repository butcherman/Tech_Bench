<script setup lang="ts">
defineProps<{
    data: workbookElement[];
}>();
</script>

<template>
    <div>
        <template v-for="component in data" :key="data.index">
            <component
                :is="component.tag"
                :class="component.class"
                v-html="component.text"
            />
        </template>
    </div>
</template>
