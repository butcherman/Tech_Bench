<template>
    <Card title="User Administration">
        <template #append-title>
            <AddButton
                text="New User"
                size="small"
                :href="$route('admin.user.create')"
                pill
            />
        </template>
        <DataTable
            :columns="columns"
            :rows="userList"
            :loading="tableLoading"
            :row-click-link="(row) => $route('admin.user.show', row.username)"
            paginate
            striped
            sync-loading-state
        >
            <template #row.actions="{ rowData }">
                <BaseBadge
                    icon="user-slash"
                    class="pointer"
                    variant="danger"
                    v-tooltip.left="'Disable User'"
                    @click.stop="disableUser(rowData)"
                />
            </template>
        </DataTable>
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import { ref } from "vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    userList: user[];
}>();

const tableLoading = ref(false);

const disableUser = (row: user) => {
    verifyModal("This User Will No Longer Be Allowed Access").then((res) => {
        if (res) {
            router.delete(route("admin.user.destroy", row.username), {
                preserveScroll: true,
                async: true,
            });
        }
    });
};

const columns = [
    {
        label: "Name",
        field: "full_name",
        sort: true,
        filterable: true,
    },
    {
        label: "Username",
        field: "username",
        sort: true,
        filterable: true,
    },
    {
        label: "Email",
        field: "email",
        sort: true,
        filterable: true,
    },
    {
        label: "Role",
        field: "role_name",
        sort: true,
        filterable: true,
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
