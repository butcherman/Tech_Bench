<template>
    <div>
        <Card title="User Administration">
            <template #append-title>
                <AddButton size="small" text="New User" pill />
            </template>
            <v-data-table :items="userList" :headers="columns">
                <template #item.actions>
                    <v-chip
                        color="red"
                        size="x-small"
                        class="pointer"
                        v-tooltip="'Disable User'"
                    >
                        <font-awesome-icon icon="user-slash" />
                    </v-chip>
                </template>
            </v-data-table>
        </Card>
    </div>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { ref, reactive, onMounted } from "vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";

const props = defineProps<{
    userList: user[];
}>();

const columns = [
    {
        title: "Name",
        value: "full_name",
        // sort: true,
        // filterOptions: {
        //     enabled: true,
        //     placeholder: "Filter Name",
        // },
    },
    {
        title: "Username",
        value: "username",
        // sort: true,
        // filterOptions: {
        //     enabled: true,
        //     placeholder: "Filter Username",
        // },
    },
    {
        title: "Email",
        value: "email",
        // sort: true,
        // filterOptions: {
        //     enabled: true,
        //     placeholder: "Filter Email",
        // },
    },
    {
        title: "Role",
        value: "role_name",
        // sort: true,
    },
    {
        value: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
