<template>
    <div id="tech-tip-wrapper">
        <Head :title="tipData.subject" />
        <div class="border-bottom border-secondary-subtle pb-2">
            <TipDetailsTitle :tip-data="tipData" />
        </div>
        <TipEquipmentList :tip-equipment="tipEquipment" />
        <div class="border-top border-bottom border-danger p-4 m-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Actions:</div>
                    <div class="text-center">
                        <Link
                            :href="
                                $route(
                                    'admin.tech-tips.restore',
                                    tipData.tip_id
                                )
                            "
                            class="btn btn-primary mx-1 w-50 my-2"
                        >
                            <fa-icon icon="rotate" />
                            Restore Tip
                        </Link>
                        <DeleteButton
                            class="w-50 my-2"
                            text="Delete Tip"
                            @click="verifyDelete"
                        />
                    </div>
                </div>
            </div>
        </div>
        <TipDetails :tip-data="tipData" class="mt-4" />
        <TipFiles :tip-files="tipFiles" class="mt-4" />
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TipDetailsTitle from "@/Components/TechTips/TipDetailsTitle.vue";
import TipEquipmentList from "@/Components/TechTips/TipEquipmentList.vue";
import TipDetails from "@/Components/TechTips/TipDetails.vue";
import TipFiles from "@/Components/TechTips/TipFiles.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";

import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    tipData: techTip;
    tipEquipment: equipment[];
    tipFiles: fileUpload[];
    tipComments: tipComment[];
}>();

const verifyDelete = () => {
    verifyModal("This action cannot be undone").then((res) => {
        if (res) {
            router.delete(
                route("admin.tech-tips.force-delete", props.tipData.tip_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
