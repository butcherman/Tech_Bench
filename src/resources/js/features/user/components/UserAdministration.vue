<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import DataTable from "@/features/dataTable/DataTable.vue";
import { useUserAdministrationHelper } from "../composables/userAdministrationHelper";

defineProps<{
    userList: User[];
}>();

const { userTableColumns } = useUserAdministrationHelper();
</script>

<template>
    <div>
        <DataTable
            :columns="userTableColumns"
            :data="userList"
            no-results-text="No Users Found"
            striped
            grid-lines
            actions-slot
        >
            <template #header.actions>&nbsp;</template>
            <template #row.actions>
                <BaseBadge icon="user-slash" variant="danger" circle />
            </template>
        </DataTable>
    </div>
</template>
