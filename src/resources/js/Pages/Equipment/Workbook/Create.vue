<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
import WorkbookEditor from "@/Components/Equipment/WorkbookEditor/WorkbookEditor.vue";

defineProps<{
    equipmentType: equipment;
    workbookData: workbookWrapper;
}>();
</script>

<script lang="ts">
export default { layout: LinkLayout };
</script>

<template>
    <div>
        <div class="mb-2">
            <BaseButton
                icon="arrow-left"
                text="Back to Tech Bench"
                :href="$route('workbooks.index')"
            />
        </div>
        <div>
            <WorkbookEditor
                :equipment-type="equipmentType"
                :workbook-data="workbookData"
            />
        </div>
    </div>
</template>
