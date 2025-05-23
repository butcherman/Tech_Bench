<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import KbLayout from "@/Layouts/KnowledgeBase/KbLayout.vue";
import TipData from "@/Components/TechTips/Show/TipData.vue";
import TipEquipment from "@/Components/TechTips/Show/TipEquipment.vue";
import TipFiles from "@/Components/TechTips/Show/TipFiles.vue";

defineProps<{
    equipment: equipment[];
    files: fileUpload[];
    techTip: techTip;
}>();
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>

<template>
    <div class="flex flex-col">
        <div>
            <BaseButton
                icon="left-long"
                text="Back to Search"
                class="mb-3"
                :href="$route('publicTips.index')"
            />
        </div>
        <Card>
            <div class="pb-2 border-b border-slate-400">
                <div class="flex gap-2">
                    <h1 class="grow">
                        {{ techTip.subject }}
                    </h1>
                </div>
                <div class="flex gap-2">
                    <TipData :tech-tip="techTip" class="grow" is-public />
                </div>
            </div>
            <div class="pb-2 border-b border-slate-400">
                <TipEquipment :equipment-list="equipment" />
            </div>
        </Card>
        <div class="my-4">
            <Card
                ><div v-html="techTip.details" class="overflow-auto min-h-60"
            /></Card>
        </div>
        <div v-if="files.length" class="flex justify-center">
            <TipFiles :files="files" />
        </div>
    </div>
</template>
