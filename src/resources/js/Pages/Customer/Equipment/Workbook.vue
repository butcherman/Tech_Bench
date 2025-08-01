<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { computed } from "vue";
import { customer } from "@/Composables/Customer/CustomerData.module";

const props = defineProps<{
    equipment: customerEquipment;
    workbookData: string;
    values?: { [index: string]: string };
    workbookHash?: string;
}>();

const wbData = computed(() => JSON.parse(props.workbookData));
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
                    text="Back to Tech Bench"
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
                    :href="$route('customer-workbook.edit', workbookHash)"
                    target="_blank"
                    class="text-blue-600"
                >
                    {{ $route("customer-workbook.edit", workbookHash) }}
                </a>
                <ClipboardCopy
                    class="ms-1"
                    :value="$route('customer-workbook.edit', workbookHash)"
                />
            </div>
            <div>
                <!-- <SwitchInput
                    id="publish"
                    name="publish"
                    label="Publish Workbook"
                    reverse
                />
                <p class="text-end text-sm text-muted">
                    Available until 07-23-2025
                </p> -->
            </div>
        </div>
        <WorkbookBase
            class="grow"
            :workbook-data="wbData"
            :active-page="wbData.body[0].page"
            :values="values"
            :workbook-hash="workbookHash"
        />
    </div>
</template>
