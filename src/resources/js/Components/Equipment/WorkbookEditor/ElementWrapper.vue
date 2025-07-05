<script setup lang="ts">
const props = defineProps<{
    componentList?: any[];
}>();
</script>

<template>
    <template v-for="comp in componentList">
        <template v-if="comp.type === 'wrapper'">
            <component :is="comp.tag" :class="comp.class">
                <ElementWrapper :component-list="comp.container" />
            </component>
        </template>
        <template v-else>
            <component
                :is="comp.tag"
                :class="comp.class"
                v-bind="comp.props"
                v-html="comp.text"
            />
        </template>
    </template>
</template>
