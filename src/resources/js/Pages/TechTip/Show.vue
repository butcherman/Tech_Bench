<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import ManageTip from "@/Components/TechTips/Show/ManageTip.vue";
import TipData from "@/Components/TechTips/Show/TipData.vue";
import TipEquipment from "@/Components/TechTips/Show/TipEquipment.vue";

const props = defineProps<{
    permissions: techTipPermissions;
    techTip: techTip;
    equipment: equipment[];
    files: fileUpload[];
    isFav: boolean;
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
            </div>
        </div>
        <div class="pb-2 border-b border-slate-400">
            <TipEquipment :equipment-list="equipment" />
        </div>
    </div>
</template>
