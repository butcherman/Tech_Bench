<template>
    <div>
        <Head title="Equipment Data Types" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Data Types</div>
                        <p class="text-center">
                            When equipment is assigned to a customer, the
                            following Data Types are available to gather
                            information for that equipment.
                        </p>
                        <p class="text-center">
                            <strong>Note:</strong> Data Types that are in use
                            cannot be deleted until they have been removed from
                            all Equipment Types
                        </p>
                        <hr />
                        <Table
                            :columns="tableColumns"
                            :rows="dataTypes"
                            responsive
                            paginate
                        >
                            <template #action="{ rowData }">
                                <Link
                                    :href="
                                        $route(
                                            'equipment-data.edit',
                                            rowData.type_id
                                        )
                                    "
                                    class="float-end"
                                >
                                    <EditBadge />
                                </Link>
                                <DeleteBadge
                                    v-if="!rowData.in_use"
                                    class="float-end"
                                    @click="verifyDeleteType(rowData)"
                                />
                            </template>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/_Base/Table.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    dataTypes: any[];
}>();

const verifyDeleteType = (type: dataTypes) => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            console.log("do it", type);
            router.delete(route("equipment-data.destroy", type.type_id));
        }
    });
};

const tableColumns = [
    {
        label: "Name",
        field: "name",
        filterOptions: {
            enabled: true,
        },
    },
    {
        label: "Pattern",
        field: "pattern",
    },
    {
        label: "Allow Copy",
        field: "allow_copy",
        isBoolean: true,
    },
    {
        label: "Masked",
        field: "masked",
        isBoolean: true,
    },
    {
        label: "Hyperlink",
        field: "is_hyperlink",
        isBoolean: true,
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
