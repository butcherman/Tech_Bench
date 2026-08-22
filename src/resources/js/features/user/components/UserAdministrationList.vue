<script setup lang="ts">
import DataTable from "@/core/features/dataResources/DataTable.vue";
import DeleteBadge from "@/core/components/badges/DeleteBadge.vue";
import { show, destroy } from "@/wayfinder/routes/admin/user";
import { router } from "@inertiajs/vue3";
import { useColumnBuilder } from "@/core/features/dataResources/composables/columnBuilder";
import { useLinkHelper } from "@/core/composables/linkHelper";
import { useUserState } from "../state/userState";

defineProps<{
    userList: User[];
}>();

const { authorizedUser } = useUserState();

const colHelper = useColumnBuilder<User>();

const tableColumns = [
    colHelper.text("full_name", "Name"),
    colHelper.text("username", "Username"),
    colHelper.text("email", "Email"),
    colHelper.text("role_name", "Role", {
        filterSelect: true,
    }),
];

/**
 * When a row is clicked on, go to that users profile
 */
const onRowClick = (event: MouseEvent, userRow: User): void => {
    const linkInfo = {
        href: show.url(userRow.username),
    };

    useLinkHelper(event, linkInfo);
};

/**
 * Disable a user
 */
const onDisableUser = (userRow: User): void => {
    if (authorizedUser && userRow.username === authorizedUser.value?.username) {
        alert("You cannot disable yourself");
        return;
    }

    router.delete(destroy.url(userRow.username), {
        preserveScroll: true,
        async: true,
    });
};
</script>

<template>
    <div>
        <DataTable
            v-if="userList"
            :data="userList"
            :columns="tableColumns"
            :row-click-fn="onRowClick"
            paginate
            striped
            actions-slot
        >
            <template #row.actions="{ rowData }">
                <DeleteBadge
                    v-if="rowData.username !== authorizedUser?.username"
                    icon="user-slash"
                    circle
                    confirm
                    v-tooltip.left="'Disable User'"
                    @yes-clicked="onDisableUser(rowData)"
                />
            </template>
        </DataTable>
    </div>
</template>
