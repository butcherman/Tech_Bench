<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import PublishWorkbook from "@/Components/Workbook/PublishWorkbook.vue";
import WorkbookWrapper from "@/Components/Workbook/WorkbookWrapper.vue";
import { activePage } from "@/Composables/Workbook/CustomerWorkbook.module";
import { onMounted } from "vue";

const props = defineProps<{
    customer: customer;
    equipment: customerEquipment;
    workbook: customerWorkbook;
    // wbValues?: { [key: string]: string };
}>();

onMounted(() => {
    activePage.value = props.workbook.wb_skeleton.body[0].page;
});
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="mb-2 pb-2 flex border-b border-slate-300">
            <div>
                <BaseButton
                    icon="arrow-left"
                    text="Back to Equipment"
                    :href="
                        $route('customers.equipment.show', [
                            customer.slug,
                            equipment.cust_equip_id,
                        ])
                    "
                />
            </div>
            <div class="text-center grow">
                <div v-if="workbook.published">
                    <p>Public Workbook Link</p>
                    <p>
                        <a
                            :href="
                                $route(
                                    'cust-workbook.show',
                                    props.workbook.wb_hash,
                                )
                            "
                            target="_blank"
                            class="text-blue-600 me-2"
                        >
                            {{
                                $route(
                                    "cust-workbook.show",
                                    props.workbook.wb_hash,
                                )
                            }}
                        </a>
                        <ClipboardCopy
                            :value="
                                $route(
                                    'cust-workbook.show',
                                    props.workbook?.wb_hash,
                                )
                            "
                        />
                    </p>
                </div>
            </div>
            <div>
                <PublishWorkbook :equipment="equipment" :workbook="workbook" />
            </div>
        </div>
        <div class="grow">
            <WorkbookWrapper :workbook-skeleton="workbook.wb_skeleton" />
        </div>
    </div>
</template>
