<template>
    <Card title="User Administration">
        <template #append-title>
            <AddButton
                size="small"
                text="New User"
                :href="$route('admin.user.create')"
                pill
            />
        </template>
        <div class="md:w-1/3 md:float-end">
            <v-text-field
                v-model="filterData"
                density="compact"
                prepend-inner-icon="magnifying-glass"
                variant="solo"
                placeholder="Filter"
                clearable
            />
        </div>
        <v-data-table
            :items="userList"
            :headers="columns"
            :search="filterData"
            :loading="userList ? false : true"
            @click:row="handleRowClick"
        >
            <template #item.actions="{ item }">
                <v-chip
                    color="red"
                    size="x-small"
                    class="pointer"
                    v-tooltip="'Disable User'"
                    @click.stop="disableUser(item)"
                >
                    <font-awesome-icon icon="user-slash" />
                </v-chip>
            </template>
        </v-data-table>
    </Card>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import VerifyModal from "@/Modules/VerifyModal";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    userList?: user[];
}>();

const filterData = ref<string>("");

/**
 * Navigate to the Show User Details page
 */
const handleRowClick = (event: MouseEvent, row: { item: user }): void => {
    let location = route("admin.user.show", row.item.username);

    if (event.ctrlKey) {
        window.open(location);
    } else {
        router.get(location);
    }
};

/**
 * Soft Delete/Disable User
 */
const disableUser = (user: user): void => {
    VerifyModal("This users access will immediately be revoked").then((res) => {
        if (res) {
            router.delete(route("admin.user.destroy", user.username));
        }
    });
};

const columns = [
    {
        title: "Name",
        value: "full_name",
        sortable: true,
    },
    {
        title: "Username",
        value: "username",
        sortable: true,
    },
    {
        title: "Email",
        value: "email",
        sortable: true,
    },
    {
        title: "Role",
        value: "role_name",
        sortable: true,
    },
    {
        value: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
