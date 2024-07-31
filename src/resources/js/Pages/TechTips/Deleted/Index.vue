<template>
    <div>
        <Head title="Disabled Tech Tips" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Disabled Tech Tips</div>
                        <Table :columns="columns" :rows="deletedTips">
                            <template #action="{ rowData }">
                                <Link
                                    :href="
                                        $route(
                                            'admin.tech-tips.deleted-tips.show',
                                            rowData.tip_id
                                        )
                                    "
                                >
                                    <span title="View Tech Tip" v-tooltip>
                                        <fa-icon icon="eye" />
                                    </span>
                                </Link>
                                <RestoreBadge @click="restoreTip(rowData)" />
                                <DeleteBadge @click="destroyTip(rowData)" />
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
import RestoreBadge from "@/Components/_Base/Badges/RestoreBadge.vue";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

defineProps<{
    deletedTips: techTip[];
}>();

const columns = [
    {
        label: "Tip ID",
        field: "tip_id",
    },
    {
        label: "Subject",
        field: "subject",
    },
    {
        label: "Date Deleted",
        field: "deleted_at",
    },
];

const restoreTip = (techTip: techTip) => {
    router.get(route("admin.tech-tips.restore", techTip.tip_id));
};

const destroyTip = (techTip: techTip) => {
    verifyModal("This action cannot be undone").then((res) => {
        if (res) {
            router.delete(
                route("admin.tech-tips.force-delete", techTip.tip_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
