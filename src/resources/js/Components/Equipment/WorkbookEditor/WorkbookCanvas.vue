<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import okModal from "@/Modules/okModal";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import WorkbookFooter from "./WorkbookFooter.vue";
import { computed, unref } from "vue";
import { dataPut } from "@/Composables/axiosWrapper.module";
import { useAppStore } from "@/Stores/AppStore";
import {
    equipmentType,
    isDirty,
    resetWorkbookData,
    updateSavedWorkbook,
    workbookData,
} from "@/Composables/Equipment/WorkbookEditor";

const appStore = useAppStore();
const dirtyVariant = computed(() => (isDirty.value ? "warning" : "primary"));

/**
 * Update the Saved Data in the workbook database
 */
const saveWorkbook = () => {
    if (
        workbookData.value.body.length == 1 &&
        !workbookData.value.body[0].container.length
    ) {
        okModal(
            "Cannot Save an Empty Workbook.  Please build Workbook Data First"
        );
        return;
    }

    dataPut(route("workbooks.update", equipmentType.value?.equip_id), {
        workbook_data: unref(workbookData),
    }).then((res) => {
        if (res && res.data.success) {
            isDirty.value = false;
            updateSavedWorkbook();
            appStore.pushFlashMsg({
                id: "new",
                type: "success",
                message: "Workbook Saved",
            });
        }
    });
};

const resetWorkbook = () => {
    if (isDirty.value) {
        resetWorkbookData();
        isDirty.value = false;
        appStore.pushFlashMsg({
            id: "new",
            type: "warning",
            message: "Changes Removed",
        });
    }
};
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <template #append-title>
            <div class="flex gap-1">
                <a
                    v-if="equipmentType"
                    :href="$route('workbooks.show', equipmentType?.equip_id)"
                    target="_blank"
                >
                    <BaseBadge
                        icon="eye"
                        variant="help"
                        v-tooltip.left="'Preview'"
                    />
                </a>
                <BaseBadge
                    icon="rotate-left"
                    variant="danger"
                    v-tooltip.left="'Undo Changes Since Last Save'"
                    :disabled="!isDirty"
                    @click="resetWorkbook()"
                />
                <BaseBadge
                    icon="save"
                    :variant="dirtyVariant"
                    v-tooltip.left="'Save Workbook'"
                    @click="saveWorkbook()"
                />
            </div>
        </template>
        <div class="flex flex-col h-full gap-2">
            <WorkbookHeader />
            <WorkbookBody class="grow" />
            <WorkbookFooter />
        </div>
    </Card>
</template>
