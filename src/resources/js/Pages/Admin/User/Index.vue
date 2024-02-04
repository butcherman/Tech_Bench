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
                            @on-row-click="rowClicked"
                        >
                            <template #action="{ rowData }">
                                <span
                                    class="badge bg-danger rounded-pill mx-1"
                                    title="Disable"
                                    v-tooltip
                                    @click="disableUser(rowData)"
                                >
                                    <fa-icon icon="user-slash" />
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
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    userList: user[];
}>();

const disableUser = (user: user) => {
    verifyModal(`${user.full_name} will be immediately disabled`).then(
        (res) => {
            console.log(res);
            if (res) {
                router.delete(route("admin.user.destroy", user.username));
            }
        }
    );
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
            // placeholder: "Search",
        },
    },
    {
        label: "Username",
        field: "username",
        sort: true,
        filterOptions: {
            enabled: true,
            // placeholder: "Search",
        },
    },
    {
        label: "Email",
        field: "email",
        sort: true,
        filterOptions: {
            enabled: true,
            // placeholder: "Search",
        },
    },
    {
        label: "Role",
        field: "role_name",
        sort: true,
        filterOptions: {
            enabled: true,
            // placeholder: "Search",
        },
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
