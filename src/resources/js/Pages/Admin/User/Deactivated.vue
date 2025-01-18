<template>
    <Card title="User Administration">
        <DataTable
            :rows="userList"
            :columns="columns"
            :row-click-link="(row) => $route('admin.user.show', row.username)"
            striped
            sync-loading-state
        >
            <template #row.actions="{ rowData }">
                <BaseBadge
                    icon="unlock-alt"
                    class="pointer"
                    variant="warning"
                    v-tooltip.left="'Enable User'"
                    @click.stop="enableUser(rowData)"
                />
            </template>
        </DataTable>
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    userList: user[];
}>();

/**
 * Re-Enable a soft deleted user
 */
const enableUser = (user: user) => {
    verifyModal(`${user.full_name} will be immediately enabled`).then((res) => {
        if (res) {
            router.get(route("admin.user.restore", user.username));
        }
    });
};

const columns = [
    {
        label: "Name",
        field: "full_name",
        sort: true,
        filer: true,
    },
    {
        label: "Username",
        field: "username",
        sort: true,
        filer: true,
    },
    {
        label: "Email",
        field: "email",
        sort: true,
        filer: true,
    },
    {
        label: "Role",
        field: "role_name",
        sort: true,
        filer: true,
        filterSelect: true,
    },
    {
        field: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
