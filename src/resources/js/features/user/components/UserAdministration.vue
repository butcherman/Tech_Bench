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

/**
 * When a row is clicked on, go to that users profile
 */
const rowLink = (event: MouseEvent, userRow: User): void => {
    const linkInfo = {
        href: show.url(userRow.username),
    };

    useLinkHelper(event, linkInfo);
};

/**
 * Disable a user
 */
const disableUser = (userRow: User): void => {
    console.log(userRow);
};
</script>

<template>
    <div>
        <DataTable
            :columns="userTableColumns"
            :data="userList"
            :row-link-fn="rowLink"
            no-results-text="No Users Found"
            striped
            grid-lines
            actions-slot
            paginate
        >
            <template #header.actions>&nbsp;</template>
            <template #row.actions="{ rowData }">
                <BaseBadge
                    icon="user-slash"
                    variant="danger"
                    pointer
                    circle
                    @click="disableUser(rowData)"
                />
            </template>
        </DataTable>
    </div>
</template>
