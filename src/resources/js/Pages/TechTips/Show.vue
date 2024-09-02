<template>
    <div id="tech-tip-wrapper">
        <Head :title="tipData.subject" />
        <div class="border-bottom border-secondary-subtle pb-2">
            <TipManagement
                v-if="showManage"
                :tip-data="tipData"
                :permissions="permissions"
                class="float-end"
            />
            <h3 class="float-start mx-2">
                <BookmarkItem
                    :is-bookmark="isFav"
                    :toggle-route="$route('tech-tips.bookmark', tipData.slug)"
                />
            </h3>
            <TipDetailsTitle :tip-data="tipData" />
        </div>
        <TipEquipmentList :tip-equipment="tipEquipment" />
        <TipDetails
            :tip-data="tipData"
            :out-of-date="outOfDate"
            class="mt-4"
            @refreshed="outOfDate = false"
        />
        <TipFiles :tip-files="tipFiles" class="mt-4" />
        <TipComments
            v-if="
                (tipData.allow_comments && permissions.comment) ||
                commentList.length
            "
            :tip-comments="commentList"
            :tip-slug="tipData.slug"
            :permissions="permissions"
            class="mt-4"
        />
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TipDetailsTitle from "@/Components/TechTips/TipDetailsTitle.vue";
import TipManagement from "@/Components/TechTips/TipManagement.vue";
import TipEquipmentList from "@/Components/TechTips/TipEquipmentList.vue";
import TipDetails from "@/Components/TechTips/TipDetails.vue";
import TipFiles from "@/Components/TechTips/TipFiles.vue";
import TipComments from "@/Components/TechTips/TipComments.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import { ref, computed, onMounted } from "vue";
import { useAppStore } from "@/Store/AppStore";

const props = defineProps<{
    tipData: techTip;
    tipEquipment: equipment[];
    tipFiles: fileUpload[];
    tipComments: tipComment[];
    permissions: techTipPermissions;
    isFav: boolean;
}>();

const appStore = useAppStore();

onMounted(() => {
    Echo.private(`tech-tips.${props.tipData.tip_id}`)
        .listen(".TechTipEvent", function (tip: techTip) {
            console.log(tip);
            if (tip.updated_id !== appStore.user?.user_id) {
                outOfDate.value = true;
            }
        })
        .listen(
            ".TechTipCommentEvent",
            function (comment: { comment: tipComment }) {
                newComments.value.push(comment.comment);
            }
        );
});

const newComments = ref<tipComment[]>([]);
const commentList = computed<tipComment[]>(() =>
    props.tipComments.concat(newComments.value)
);

const outOfDate = ref(false);

const showManage = computed(() => {
    if (
        props.permissions.manage ||
        props.permissions.update ||
        props.permissions.delete
    ) {
        return true;
    }

    return false;
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
