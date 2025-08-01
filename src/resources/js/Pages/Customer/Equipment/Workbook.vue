<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import ToggleSwitch from "@/Components/_Base/ToggleSwitch.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { computed, ref } from "vue";
import { customer } from "@/Composables/Customer/CustomerData.module";

interface workbookObject {
    wb_id: number;
    published: boolean;
    by_invite_only: boolean;
    publish_until: string | null;
}

const props = defineProps<{
    equipment: customerEquipment;
    workbookData: string;
    values?: { [index: string]: string };
    workbookHash?: string;
    workbook: workbookObject;
}>();

const wbData = computed(() => JSON.parse(props.workbookData));
const isPublished = ref(props.workbook.published);

const publishWorkbook = () => {
    console.log(isPublished.value);
};
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
                <ToggleSwitch
                    v-model="isPublished"
                    id="publish"
                    name="publish"
                    label="Publish Workbook"
                    reverse
                    @change="publishWorkbook()"
                />
                <p
                    v-if="workbook.published && workbook.publish_until"
                    class="text-end text-sm text-muted"
                >
                    Available until {{ workbook.publish_until }}
                </p>
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
