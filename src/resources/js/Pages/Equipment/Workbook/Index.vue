<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import AddBadge from "@/Components/_Base/Badges/AddBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";

const props = defineProps<{
    equipmentList: equipmentCategory[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <Card v-for="category in equipmentList" :title="category.name">
            <!-- <h4 class="text-center">Coming Soon</h4> -->
            <ResourceList :list="category.equipment_type" label-field="name">
                <template #actions="{ item }">
                    <AddBadge
                        :href="
                            $route('equipment.workbooks.create', item.equip_id)
                        "
                        v-tooltip="'Add Workbook'"
                    />
                    <EditBadge
                        :href="
                            $route('equipment.workbooks.edit', item.equip_id)
                        "
                        v-tooltip="'Modify Workbook'"
                    />
                </template>
            </ResourceList>
        </Card>
    </div>
</template>
