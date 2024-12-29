<template>
    <Card title="User Administration">
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
                    color="yellow-darken-3"
                    size="x-small"
                    class="pointer"
                    v-tooltip="'Enable User'"
                    @click.stop="enableUser(item)"
                >
                    <font-awesome-icon icon="unlock-alt" />
                </v-chip>
            </template>
        </v-data-table>
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref } from "vue";
import VerifyModal from "@/Modules/VerifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    userList: user[];
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
 * Re-Enable a soft deleted user
 */
const enableUser = (user: user) => {
    VerifyModal(`${user.full_name} will be immediately enabled`).then((res) => {
        if (res) {
            router.get(route("admin.user.restore", user.username));
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
