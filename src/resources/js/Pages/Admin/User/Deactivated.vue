<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import verifyModal from "@/Modules/verifyModal";
import { Deferred, router } from "@inertiajs/vue3";

defineProps<{
    userList?: user[];
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

<template>
    <div class="flex justify-center">
        <Card class="tb-card-lg overflow-x-auto" title="User Administration">
            <template #append-title>
                <AddButton
                    text="New User"
                    :href="$route('admin.user.create')"
                    size="small"
                    pill
                />
            </template>
            <Deferred data="user-list">
                <template #fallback>
                    <div class="flex justify-center">
                        <div>
                            <AtomLoader class="mx-auto" />
                            Loading Users
                        </div>
                    </div>
                </template>
                <DataTable
                    v-if="userList"
                    :columns="columns"
                    :rows="userList"
                    :row-click-link="
                        (row) => $route('admin.user.show', row.username)
                    "
                    paginate
                    striped
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
            </Deferred>
        </Card>
    </div>
</template>
