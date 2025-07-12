<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import AddBadge from "@/Components/_Base/Badges/AddBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";

defineProps<{
    equipmentList: equipmentCategory[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
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
                >
                    <template #actions="{ item }">
                        <AddBadge
                            v-if="!item.has_workbook"
                            :href="$route('workbooks.create', item.equip_id)"
                            v-tooltip="'Create Workbook'"
                        />
                        <EditBadge
                            v-if="item.has_workbook"
                            :href="$route('workbooks.edit', item.equip_id)"
                            v-tooltip="'Edit Workbook'"
                        />
                    </template>
                </ResourceList>
            </Card>
        </div>
    </div>
</template>
