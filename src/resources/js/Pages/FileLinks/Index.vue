<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <Link :href="$route('links.create')" class="float-end">
                            <AddButton text="New File Link" pill small />
                        </Link>
                        File Upload Links
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Link Name</th>
                                    <th>Expires</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template
                                    v-for="link in linkList"
                                    :key="link.link_id"
                                >
                                    <tr
                                        class="row-link"
                                        :class="{
                                            'table-danger': link.is_expired,
                                        }"
                                    >
                                        <td>
                                            <Link
                                                :href="link.href"
                                                class="block-link"
                                            >
                                                {{ link.link_name }}
                                            </Link>
                                        </td>
                                        <td>
                                            <Link
                                                :href="link.href"
                                                class="block-link"
                                            >
                                                {{ link.expire }}
                                            </Link>
                                        </td>
                                        <td>
                                            <DeleteBadge
                                                class="mt-2 float-end"
                                                @click="deleteLink(link)"
                                            />
                                            <span
                                                v-if="!link.is_expired"
                                                class="badge bg-warning rounded-pill pointer mx-1 mt-2 float-end"
                                                title="Disable Link"
                                                v-tooltip
                                                @click="disableLink(link)"
                                            >
                                                <fa-icon icon="link-slash" />
                                            </span>
                                            <ClipboardCopy
                                                v-if="!link.is_expired"
                                                :value="link.public_href"
                                                title="Copy Public Link to Clipboard"
                                                class="mt-2"
                                            />
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
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
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ClipboardCopy from "@/Components/_Base/Badges/ClipboardCopy.vue";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

defineProps<{
    linkList: fileLink[];
}>();

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
