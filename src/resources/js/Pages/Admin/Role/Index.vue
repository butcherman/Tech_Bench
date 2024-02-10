<template>
    <div class="row justify-content-center">
        <Head title="Roles and Permissions" />
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Select A Role
                        <Link
                            :href="$route('admin.user-roles.create')"
                            class="float-end"
                        >
                            <AddButton small pill>Create New Role</AddButton>
                        </Link>
                    </div>
                    <Table
                        :columns="columns"
                        :rows="roles"
                        row-clickable
                        @on-row-click="onRowClick"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import Table from "@/Components/_Base/Table.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    roles: userRole[];
}>();

const columns = [
    {
        label: "Name",
        field: "name",
    },
    {
        label: "Description",
        field: "description",
    },
];

const onRowClick = (row: userRole) => {
    router.get(route("admin.user-roles.show", row.role_id));
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
