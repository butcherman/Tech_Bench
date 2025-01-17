<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <template v-for="(items, group) in menu" :key="group">
            <Card v-if="items.length" :title="group.toString()">
                <ResourceList
                    label-field="name"
                    :list="items"
                    :link-fn="(row) => row.route"
                    no-border
                    compact
                >
                    <template #list-item="myItem">
                        <span class="text-muted">
                            <fa-icon :icon="myItem.item.icon" />
                            {{ myItem.item.name }}
                        </span>
                    </template>
                </ResourceList>
            </Card>
        </template>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

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
