<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import DataTable from "@/features/dataTable/DataTable.vue";
import { show } from "@/wayfinder/routes/admin/user";
import { useLinkHelper } from "@/core/composables/linkHelper";
import { useUserAdministrationHelper } from "../composables/userAdministrationHelper";

defineProps<{
    userList: User[];
}>();

const { userTableColumns } = useUserAdministrationHelper();

const rowLink = (event: MouseEvent, userRow: User): void => {
    const linkInfo = {
        href: show.url(userRow.username),
    };

    useLinkHelper(event, linkInfo);
};
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
            paginate
            :row-link-fn="rowLink"
        >
            <template #header.actions>&nbsp;</template>
            <template #row.actions>
                <BaseBadge icon="user-slash" variant="danger" circle />
            </template>
        </DataTable>
    </div>
</template>
