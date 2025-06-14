<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AddEquipmentModal from "./AddEquipmentModal.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import Pagination from "@/Components/_Base/Pagination.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import SelectEquipmentModal from "./SelectEquipmentModal.vue";
import VpnData from "./VpnData.vue";
import { Deferred } from "@inertiajs/vue3";
import { handleLinkClick } from "@/Composables/links.module";
import { computed, ref, useTemplateRef } from "vue";
import {
    allowVpn,
    customer,
    groupedEquipmentList,
} from "@/Composables/Customer/CustomerData.module";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";

const selectModal = useTemplateRef("select-equipment-modal");
const addModal = useTemplateRef("add-equipment-modal");

/*
|-------------------------------------------------------------------------------
| Pagination Data
|-------------------------------------------------------------------------------
*/
const currentPage = ref<number>(0);
const displayPage = computed<number>(() => currentPage.value + 1);
const currentChunk = computed<{ [key: string]: customerEquipment[] }>(
    () => groupedEquipmentList.value[currentPage.value]
);

const goToPage = (page: number): void => {
    currentPage.value = page - 1;
};

/*
|-------------------------------------------------------------------------------
| Loading State
|-------------------------------------------------------------------------------
*/
const isLoading = ref<boolean>(false);

/**
 * Start the loading process when the refresh button clicked.
 */
const onRefreshStart = (): void => {
    isLoading.value = true;
};

/**
 * End the loading process and clear the alert icon.
 */
const onRefreshEnd = (): void => {
    isLoading.value = false;
    clearNotification("equipment");
};

/*
|-------------------------------------------------------------------------------
| Additional Methods
|-------------------------------------------------------------------------------
*/

/**
 * If the selected equipment exists more than once for the customer, give user
 * choices to select the equipment they wish to view.
 */
const onClickAction = (
    event: MouseEvent,
    equipData: customerEquipment[]
): void => {
    if (equipData.length === 1) {
        handleLinkClick(
            event,
            route("customers.equipment.show", [
                customer.value.slug,
                equipData[0].cust_equip_id,
            ])
        );

        return;
    }

    selectModal.value?.show(equipData);
};
</script>

<template>
    <Card>
        <template #title>
            <AlertButton
                v-if="notificationStatus.equipment"
                tooltip="New Data Available"
            />
            <RefreshButton
                :only="['equipmentList']"
                flat
                @loading-start="onRefreshStart"
                @loading-complete="onRefreshEnd"
            />
            Equipment
            <AddButton
                class="float-end"
                text="Add Equipment"
                size="small"
                pill
                @click="addModal?.show"
            />
        </template>
        <Deferred data="groupedEquipmentList">
            <template #fallback>
                <div class="flex justify-center">
                    <AtomLoader />
                </div>
            </template>
            <Overlay :loading="isLoading" class="h-full">
                <VpnData v-if="allowVpn" />
                <ul class="border rounded-lg border-collapse">
                    <li v-if="!groupedEquipmentList.length" class="text-muted">
                        <h4 class="text-center">No Equipment</h4>
                    </li>
                    <li v-for="(equip, index) in currentChunk" class="p-1">
                        <BaseButton
                            class="w-full"
                            @click="onClickAction($event, equip)"
                        >
                            {{ index }}
                            <span v-if="equip.length > 1" class="float-end">
                                ({{ equip.length }})
                            </span>
                        </BaseButton>
                    </li>
                </ul>
                <div class="flex justify-center mt-2">
                    <Pagination
                        v-if="groupedEquipmentList.length > 1"
                        :current-page="displayPage"
                        :total-pages="groupedEquipmentList.length"
                        @go-to-page="goToPage"
                        @next-page="currentPage++"
                        @prev-page="currentPage--"
                    />
                </div>
            </Overlay>
        </Deferred>
        <SelectEquipmentModal ref="select-equipment-modal" />
        <AddEquipmentModal ref="add-equipment-modal" />
    </Card>
</template>
