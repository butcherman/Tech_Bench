<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import okModal from "@/Modules/okModal";
import verifyModal from "@/Modules/verifyModal";
import { Deferred, router } from "@inertiajs/vue3";
import { useAuthStore } from "@/Stores/AuthStore";

defineProps<{
    userList?: user[];
}>();

const app = useAuthStore();

const disableUser = (row: user) => {
    if (row.username === app.user?.username) {
        okModal("You cannot disable yourself");
        return;
    }

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

<template>
    <div class="flex justify-center">
        <Card class="tb-card-lg" title="User Administration">
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
                            icon="user-slash"
                            class="pointer"
                            variant="danger"
                            v-tooltip.left="'Disable User'"
                            @click.stop="disableUser(rowData)"
                        />
                    </template>
                </DataTable>
            </Deferred>
        </Card>
    </div>
</template>
