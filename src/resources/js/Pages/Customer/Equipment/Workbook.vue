<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { computed } from "vue";

const props = defineProps<{
    equipment: customerEquipment;
    workbook: customerWorkbook;
}>();

const wbData = computed(() => JSON.parse(props.workbook.wb_skeleton));
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="mb-2 flex">
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
                <p>Public Workbook Link</p>
                <a
                    :href="$route('cust-workbook.show', workbook.wb_hash)"
                    target="_blank"
                    class="text-blue-600"
                >
                    {{ $route("cust-workbook.show", workbook.wb_hash) }}
                </a>
            </div>
            <div>publish...</div>
        </div>
        <WorkbookBase
            :workbook-data="wbData"
            :active-page="wbData.body[0].page"
        />
    </div>
</template>
