<template>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <Link :href="$route('links.create')" class="float-end">
                            <AddButton text="New File Link" pill small />
                        </Link>
                        File Upload Links
                    </div>
                    <Table
                        :columns="tableCols"
                        :rows="linkList"
                        :row-style-class="rowBgClassFn"
                        initial-sort="is_expired"
                        initial-sort-direction="desc"
                        responsive
                        striped
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
                                <ClipboardCopy
                                    v-if="!rowData.is_expired"
                                    :value="rowData.public_href"
                                    title="Copy Public URL to Clipboard"
                                    class="mt-2 float-end"
                                />
                            </div>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import Table from "@/Components/_Base/Table.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ClipboardCopy from "@/Components/_Base/Badges/ClipboardCopy.vue";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

defineProps<{
    linkList: fileLink[];
}>();

const tableCols = [
    {
        label: "Link Name",
        field: "link_name",
        sort: true,
        filterOptions: {
            enabled: true,
            placeholder: "Filter Link Name",
        },
    },
    {
        label: "Expires",
        field: "expire",
        sort: true,
        sortField: "created_stamp",
    },
];

const rowBgClassFn = (row: fileLink) => (row.is_expired ? "table-danger" : "");

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
