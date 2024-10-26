<template>
    <div>
        <Head title="Manage File Links" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Manage File Links</div>
                        <Table
                            :columns="columns"
                            :rows="linkList.data"
                            :row-style-class="rowBgClassFn"
                            initial-sort="is_expired"
                            initial-sort-direction="desc"
                            no-results-text="No File Links Found"
                            striped
                            responsive
                        >
                            <template #action="{ rowData }">
                                <div>
                                    <DeleteBadge
                                        class="mt-2 float-end"
                                        @click="deleteLink(rowData)"
                                    />
                                    <span
                                        v-if="!rowData.is_expired"
                                        class="badge bg-warning rounded-pill pointer mx-1 mt-2 float-end"
                                        title="Disable Link"
                                        v-tooltip
                                        @click="disableLink(rowData)"
                                    >
                                        <fa-icon icon="link-slash" />
                                    </span>
                                </div>
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
import Table from "@/Components/_Base/Table.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import verifyModal from "@/Modules/verifyModal";
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    linkList: { data: fileLink[] };
}>();

const rowBgClassFn = (row: fileLink) => (row.is_expired ? "table-danger" : "");

const columns = [
    {
        label: "Link Name",
        field: "link_name",
        sort: true,
    },
    {
        label: "Created By",
        field: "created_by",
        sort: true,
    },
    {
        label: "Expire",
        field: "expire",
        sort: true,
    },
];

const disableLink = (link: fileLink) => {
    verifyModal(
        "This link and its files will no longer be accessible publicly"
    ).then((res) => {
        if (res) {
            router.get(route("links.expire", link.link_id));
        }
    });
};

const deleteLink = (link: fileLink) => {
    verifyModal("This link and its files will be destroyed").then((res) => {
        if (res) {
            router.delete(route("links.destroy", link.link_id));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
