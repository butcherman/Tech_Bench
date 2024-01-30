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
                        />
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
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    userList: user[];
}>();

const rowClicked = (row: user) => {
    router.get(route("admin.user.show", row.username));
};

const columns = [
    {
        label: "Name",
        field: "full_name",
        sort: true,
    },
    {
        label: "Username",
        field: "username",
        sort: true,
    },
    {
        label: "Email",
        field: "email",
        sort: true,
    },
    {
        label: "Role",
        field: "role_name",
        sort: true,
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
