<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AddEquipmentModal from "./AddEquipmentModal.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import SelectEquipmentModal from "./SelectEquipmentModal.vue";
import { Deferred } from "@inertiajs/vue3";
import { handleLinkClick } from "@/Composables/links.module";
import { useTemplateRef } from "vue";
import {
    customer,
    equipmentList,
} from "@/Composables/Customer/CustomerData.module";

const selectModal = useTemplateRef("select-equipment-modal");
const addModal = useTemplateRef("add-equipment-modal");

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
    <Card title="Equipment">
        <template #append-title>
            <AddButton
                text="Add Equipment"
                size="small"
                pill
                @click="addModal?.show"
            />
        </template>
        <Deferred data="equipmentList">
            <template #fallback>
                <div class="flex justify-center">
                    <AtomLoader />
                </div>
            </template>
            <div>
                <ul class="border rounded-lg border-collapse">
                    <li v-for="(equip, index) in equipmentList" class="p-1">
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
            </div>
        </Deferred>
        <SelectEquipmentModal ref="select-equipment-modal" />
        <AddEquipmentModal ref="add-equipment-modal" />
    </Card>
</template>
