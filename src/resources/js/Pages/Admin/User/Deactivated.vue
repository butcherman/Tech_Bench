<template>
    <div>
        <Head title="User Administration" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            User Administration
                            <Link :href="$route('admin.user.create')">
                                <AddButton class="float-end" pill small>
                                    New User
                                </AddButton>
                            </Link>
                        </div>
                        <Table
                            :columns="columns"
                            :rows="userList"
                            initial-sort="username"
                            responsive
                            row-clickable
                            paginate
                            :per-page-default="25"
                            @on-row-click="rowClicked"
                        >
                            <template #action="{ rowData }">
                                <span
                                    class="badge bg-warning rounded-pill mx-1"
                                    title="Enable User"
                                    v-tooltip
                                    @click="enableUser(rowData)"
                                >
                                    <fa-icon icon="unlock-alt" />
                                </span>
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
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import Table from "@/Components/_Base/Table.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    userList: user[];
}>();

const enableUser = (user: user) => {
    verifyModal(`${user.full_name} will be immediately enabled`).then((res) => {
        if (res) {
            router.get(route("admin.user.restore", user.username));
        }
    });
};

const rowClicked = (row: user) => {
    router.get(route("admin.user.show", row.username));
};

const columns = [
    {
        label: "Name",
        field: "full_name",
        sort: true,
        filterOptions: {
            enabled: true,
            placeholder: "Filter Name",
        },
    },
    {
        label: "Username",
        field: "username",
        sort: true,
        filterOptions: {
            enabled: true,
            placeholder: "Filter Username",
        },
    },
    {
        label: "Email",
        field: "email",
        sort: true,
        filterOptions: {
            enabled: true,
            placeholder: "Filter Email",
        },
    },
    {
        label: "Role",
        field: "role_name",
        sort: true,
    },
    {
        label: "Deactivated Date",
        field: "deleted_at",
        sort: true,
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
