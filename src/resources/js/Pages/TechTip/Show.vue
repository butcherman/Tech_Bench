<script setup lang="ts">
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import Card from "@/Components/_Base/Card.vue";
import ManageTip from "@/Components/TechTips/Show/ManageTip.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import TipCommentList from "@/Components/TechTips/Show/TipCommentList.vue";
import TipData from "@/Components/TechTips/Show/TipData.vue";
import TipEquipment from "@/Components/TechTips/Show/TipEquipment.vue";
import TipFiles from "@/Components/TechTips/Show/TipFiles.vue";
import { onMounted, onUnmounted, ref } from "vue";

interface modelEvent {
    afterCommit: boolean;
    connection: null;
    model: techTip;
    queue: null;
}

const props = defineProps<{
    allowComments: boolean;
    allowDownload: boolean;
    equipment: equipment[];
    isFav: boolean;
    files: fileUpload[];
    permissions: techTipPermissions;
    techTip: techTip;
    comments?: techTipComment[];
}>();

const newSlug = ref<string>();
const newComment = ref<boolean>(false);

const onTipUpdated = (newTipData: modelEvent) => {
    newSlug.value = newTipData.model.slug;
};

onMounted(() => {
    Echo.private(`tech-tips.${props.techTip.tip_id}`)
        .listen(".TechTipUpdated", (data: modelEvent) => onTipUpdated(data))
        .listen(".TechTipCommentCreated", () => (newComment.value = true))
        .listen(".TechTipCommentUpdated", () => (newComment.value = true))
        .listen(".TechTipCommentDeleted", () => (newComment.value = true))
        .listen(".TechTipCommentFlagCreated", () => (newComment.value = true));
});

onUnmounted(() => Echo.leave(`tech-tips.${props.techTip.tip_id}`));
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
        <div v-if="newSlug" class="my-2 flex justify-center">
            <div class="flex justify-between w-3/4">
                <div
                    class="bg-red-600 flex py-2 px-4 rounded-lg justify-between text-white w-full"
                >
                    <div class="flex items-center">
                        <fa-icon icon="triangle-exclamation" />
                    </div>
                    <div>
                        <div class="text-center">
                            Tech Tip has been updated.
                        </div>
                        <div>
                            <BaseButton
                                :href="$route('tech-tips.show', newSlug)"
                                text="Click Here to Reload Page"
                                variant="help"
                            />
                        </div>
                    </div>
                    <div class="flex items-center">
                        <fa-icon icon="triangle-exclamation" />
                    </div>
                </div>
            </div>
        </div>
        <div class="my-4">
            <Card><div v-html="techTip.details" class="overflow-auto" /></Card>
        </div>
        <div v-if="files.length" class="flex justify-center">
            <TipFiles :files="files" />
        </div>
        <div v-if="allowComments" class="flex justify-center">
            <Card class="tb-card">
                <template #title>
                    <span v-if="newComment">
                        <AlertButton tooltip="New Data Available" />
                        <RefreshButton
                            :only="['comments']"
                            flat
                            @loading-complete="newComment = false"
                        />
                    </span>
                    Discussion
                </template>
                <TipCommentList
                    :comments="comments"
                    :permissions="permissions"
                    :tech-tip="techTip"
                />
            </Card>
        </div>
    </div>
</template>
