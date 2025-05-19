<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import Card from "@/Components/_Base/Card.vue";
import ManageTip from "@/Components/TechTips/Show/ManageTip.vue";
import TipCommentList from "@/Components/TechTips/Show/TipCommentList.vue";
import TipData from "@/Components/TechTips/Show/TipData.vue";
import TipEquipment from "@/Components/TechTips/Show/TipEquipment.vue";
import TipFiles from "@/Components/TechTips/Show/TipFiles.vue";

defineProps<{
    allowComments: boolean;
    allowDownload: boolean;
    equipment: equipment[];
    isFav: boolean;
    files: fileUpload[];
    permissions: techTipPermissions;
    techTip: techTip;
    comments?: techTipComment[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="pb-2 border-b border-slate-400">
            <div class="flex gap-2">
                <h1 class="grow">
                    <BookmarkItem
                        :is-bookmark="isFav"
                        :toggle-route="
                            $route('tech-tips.bookmark', techTip.slug)
                        "
                    />
                    {{ techTip.subject }}
                </h1>
                <ManageTip :tech-tip="techTip" :permissions="permissions" />
            </div>
            <div class="flex gap-2">
                <TipData :tech-tip="techTip" class="grow" />
                <a
                    v-if="allowDownload"
                    :href="$route('tech-tips.download', techTip.slug)"
                    class="text-blue-400"
                    v-tooltip="'Download as PDF'"
                >
                    <fa-icon icon="download" />
                </a>
            </div>
        </div>
        <div class="pb-2 border-b border-slate-400">
            <TipEquipment :equipment-list="equipment" />
        </div>
        <div class="my-4">
            <Card><div v-html="techTip.details" class="overflow-auto" /></Card>
        </div>
        <div v-if="files.length" class="flex justify-center">
            <TipFiles :files="files" />
        </div>
        <div v-if="allowComments" class="flex justify-center">
            <TipCommentList
                :comments="comments"
                :permissions="permissions"
                :tech-tip="techTip"
            />
        </div>
    </div>
</template>
