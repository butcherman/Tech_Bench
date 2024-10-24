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
                            :rows="userLinkList"
                            initial-sort="username"
                            responsive
                            paginate
                            :per-page-default="25"
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
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

type userLinkList = {
    href: string;
} & user;

const props = defineProps<{
    userList: userLinkList[];
}>();

const userLinkList = computed<userLinkList[]>(() => {
    let newList = [...props.userList];
    newList.forEach((item) => {
        item.href = route("admin.user.edit", item.username);
    });

    return newList;
});

const disableUser = (user: user) => {
    verifyModal(`${user.full_name} will be immediately disabled`).then(
        (res) => {
            if (res) {
                router.delete(route("admin.user.destroy", user.username));
            }
        }
    );
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
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
