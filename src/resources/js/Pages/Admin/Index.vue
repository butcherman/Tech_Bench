<template>
    <div class="grid md:grid-cols-3 gap-3">
        <div v-for="(items, group) in menu" :key="group">
            <Card v-if="items.length" :title="group.toString()" class="h-full">
                <ResourceList :list="items" link-name="route" no-border />
            </Card>
        </div>
    </div>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";

defineProps<{
    menu: {
        [key: string]: {
            icon: string;
            route: string;
            name: string;
        }[];
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
