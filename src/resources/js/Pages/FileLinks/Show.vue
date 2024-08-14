<template>
    <div>
        <Head title="File Link Details" />
        <div v-if="link.is_expired" class="alert alert-danger text-center">
            Link Has Expired
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Details:</div>
                        <TableStacked :rows="tableData.data" title-case />
                        <div v-if="!link.is_expired">
                            <ClipboardCopy
                                class="float-end"
                                :value="link.public_href"
                                title="Copy Link to Clipboard"
                            />
                            <strong class="me-2">Public URL:</strong>
                            <a :href="link.public_href" target="_blank">
                                {{ link.public_href }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body justify-content-center">
                        <a
                            v-if="!link.is_expired"
                            :href="`mailto:?subject=A File Link Has Been Created For You
                                &body=View the link details here: ${link.public_href}`"
                            class="btn btn-info rounded-5 m-1 w-100"
                        >
                            <fa-icon icon="envelope" />
                            Email Link
                        </a>
                        <Link
                            :href="$route('links.edit', link.link_id)"
                            class="w-100 my-1"
                        >
                            <EditButton class="w-100" text="Edit Link" pill />
                        </Link>
                        <Link
                            v-if="!link.is_expired"
                            :href="$route('links.extend', link.link_id)"
                            class="btn btn-warning rounded-5 m-1 w-100"
                        >
                            <fa-icon icon="calendar-plus" />
                            Extend Link 30 Days
                        </Link>
                        <button
                            v-if="!link.is_expired"
                            class="btn btn-warning rounded-5 m-1 w-100"
                            @click="disableLink"
                        >
                            <fa-icon icon="link-slash" />
                            Disable Link
                        </button>
                        <DeleteButton
                            class="my-1 w-100"
                            text="Delete Link"
                            pill
                            @click="deleteLink"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Files:</div>
                        <Table
                            :columns="fileColumns"
                            :rows="linkFiles"
                            no-inertia-link
                        >
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
import TableStacked from "@/Components/_Base/TableStacked.vue";
import ClipboardCopy from "@/Components/_Base/Badges/ClipboardCopy.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";

const props = defineProps<{
    link: fileLink;
    tableData: {
        data: fileLink;
    };
    linkFiles: fileUpload[];
}>();

const fileColumns = [
    {
        label: "File Name",
        field: "file_name",
    },
    {
        label: "Date Added",
        field: "created_at",
    },
    {
        label: "Added By",
        field: "upload",
    },
    {
        label: "Size",
        field: "file_size",
    },
];

const disableLink = () => {
    verifyModal(
        "This link and its files will no longer be accessible publicly"
    ).then((res) => {
        if (res) {
            router.get(route("links.expire", props.link.link_id));
        }
    });
};

const deleteLink = () => {
    verifyModal("This link and its files will be destroyed").then((res) => {
        if (res) {
            router.delete(route("links.destroy", props.link.link_id));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
