<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import CanvasBody from "./CanvasBody.vue";
import CanvasHeader from "./CanvasHeader.vue";
import Card from "@/Components/_Base/Card.vue";
import {
    equipmentType,
    onSuccessfulSave,
    resetWorkbook,
    workbookData,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import { isDirty } from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import { computed, unref } from "vue";
import { dataPost } from "@/Composables/axiosWrapper.module.js";
import { useAppStore } from "@/Stores/AppStore.js";

const appStore = useAppStore();

const dirtyVariant = computed<elementVariant>(() =>
    isDirty.value ? "warning" : "primary",
);

/**
 * Save the WB to the Database
 */
const saveWorkbook = () => {
    dataPost(route("workbooks.store", equipmentType.value?.equip_id), {
        workbook_data: unref(workbookData),
    }).then((res) => {
        console.log(res);
        if (res && res.data.success) {
            onSuccessfulSave();
            appStore.pushFlashMsg({
                id: "new",
                type: "success",
                message: "Workbook Saved",
            });
        }
    });
};
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <template #append-title>
            <div class="flex gap-1 text-sm">
                <a
                    v-if="equipmentType"
                    :href="$route('workbooks.show', equipmentType?.equip_id)"
                    target="_blank"
                >
                    <BaseBadge
                        icon="eye"
                        variant="help"
                        class="h-full"
                        v-tooltip.left="'Preview'"
                    />
                </a>
                <BaseBadge
                    icon="rotate-left"
                    variant="danger"
                    :class="{ 'opacity-50': !isDirty }"
                    :disabled="!isDirty"
                    @click="resetWorkbook"
                    v-tooltip.left="'Undo Changes Since Last Save'"
                />
                <BaseBadge
                    icon="save"
                    :class="{ 'opacity-50': !isDirty }"
                    :variant="dirtyVariant"
                    @click="saveWorkbook"
                    v-tooltip.left="'Save Workbook'"
                />
            </div>
        </template>
        <div class="h-full flex flex-col gap-5">
            <CanvasHeader />
            <CanvasBody class="grow" />
            <CanvasHeader is-footer />
        </div>
    </Card>
</template>
