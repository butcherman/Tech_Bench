<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

defineProps<{
    equipmentList: equipmentCategory[];
}>();

/**
 * Link to the Workbook Builder page
 */
const linkFn = (item: equipment) => {
    return route("workbooks.create", item.equip_id);
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <h3 class="text-center mb-3">
            Click on Equipment Type to Modify or Create Workbook
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <Card
                v-for="cat in equipmentList"
                :title="cat.name"
                :key="cat.cat_id"
            >
                <ResourceList
                    :list="cat.equipment_type"
                    empty-text="No Equipment"
                    label-field="name"
                    :link-fn="linkFn"
                >
                    <template #actions="{ item }">
                        <span
                            v-if="!item.has_workbook"
                            class="text-danger"
                            v-tooltip.bottom="'No Workbook for this Equipment'"
                        >
                            <fa-icon icon="circle-xmark" />
                        </span>
                    </template>
                </ResourceList>
            </Card>
        </div>
    </div>
</template>
