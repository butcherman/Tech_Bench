<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";

defineProps<{
    dataTypes: dataTypes[];
}>();

const tableColumns = [
    {
        label: "Name",
        field: "name",
        filterable: true,
        sort: true,
    },
    {
        label: "Pattern",
        field: "pattern",
    },
    {
        label: "Allow Copy",
        field: "allow_copy",
    },
    {
        label: "Masked",
        field: "masked",
    },
    {
        label: "Hyperlink",
        field: "is_hyperlink",
    },
    {
        field: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Equipment Data Types">
            <template #append-title>
                <AddButton
                    text="New Data Type"
                    size="small"
                    :href="$route('equipment-data.create')"
                    pill
                />
            </template>
            <p class="text-center my-2">
                When equipment is assigned to a customer, the following Data
                Types are available to gather information for that equipment.
            </p>
            <p class="text-center my-2">
                <strong>Note:</strong> Data Types that are in use cannot be
                deleted until they have been removed from all Equipment Types
            </p>
            <hr />
            <DataTable :columns="tableColumns" :rows="dataTypes">
                <template #row.actions="{ rowData }">
                    <EditBadge
                        class="me-1"
                        :href="$route('equipment-data.edit', rowData.type_id)"
                        v-tooltip="'Edit'"
                    />
                    <DeleteBadge
                        v-if="!rowData.in_use"
                        class="me-1"
                        :href="
                            $route('equipment-data.destroy', rowData.type_id)
                        "
                        v-tooltip="'Delete'"
                        confirm
                        delete-method
                    />
                </template>
            </DataTable>
        </Card>
    </div>
</template>
