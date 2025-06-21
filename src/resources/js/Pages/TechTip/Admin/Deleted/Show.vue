<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import TipData from "@/Components/TechTips/Show/TipData.vue";
import TipEquipment from "@/Components/TechTips/Show/TipEquipment.vue";
import TipFiles from "@/Components/TechTips/Show/TipFiles.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    equipment: equipment[];
    files: fileUpload[];
    techTip: techTip;
}>();

const restoreTechTip = () => {
    verifyModal("This Tech Tip will be accessible again").then((res) => {
        if (res) {
            router.get(route("admin.tech-tips.restore", props.techTip.tip_id));
        }
    });
};

const destroyTechTip = () => {
    verifyModal(
        "Delete Tech Tip and all associated data?",
        "This operation cannot be undone"
    ).then((res) => {
        if (res) {
            router.delete(
                route("admin.tech-tips.force-delete", props.techTip.tip_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="pb-2 border-b border-slate-400">
            <div class="flex gap-2">
                <h1 class="grow">
                    {{ techTip.subject }}
                </h1>
            </div>
            <div class="flex gap-2">
                <TipData :tech-tip="techTip" class="grow" />
            </div>
        </div>
        <div class="pb-2 border-b border-slate-400">
            <TipEquipment :equipment-list="equipment" />
        </div>
        <div class="flex justify-center">
            <Card title="Actions" class="tb-card">
                <div class="flex justify-center">
                    <BaseButton
                        class="w-1/3 mx-2"
                        icon="rotate"
                        text="Restore Tech Tip"
                        @click="restoreTechTip"
                    />
                    <DeleteButton
                        class="w-1/3 mx-2"
                        text="Delete Tech Tip"
                        @click="destroyTechTip"
                    />
                </div>
            </Card>
        </div>
        <div class="my-4">
            <Card><div v-html="techTip.details" class="overflow-auto" /></Card>
        </div>
        <div v-if="files.length" class="flex justify-center">
            <TipFiles :files="files" />
        </div>
    </div>
</template>
