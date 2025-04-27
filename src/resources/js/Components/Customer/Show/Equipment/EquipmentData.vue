<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import EquipmentDataInput from "./EquipmentDataInput.vue";
import EquipmentDataValue from "./EquipmentDataValue.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    customer,
    permissions,
} from "@/Composables/Customer/CustomerData.module";

type equipmentFormData = {
    fieldId: number;
    value: string;
};

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

/**
 * Loading state of component.
 */
const isLoading = ref<boolean>(false);

/**
 * List of fields that are currently being edited.
 */
const editFields = ref<number[]>([]);
const editValues = ref<string[]>([]);

/**
 * Add a single field to the Edit Array to show input.
 */
const addEditField = (data: customerEquipmentData): void => {
    editFields.value.push(data.id);
    editValues.value[data.id] = data.value;
};

/**
 * Add all fields to the Edit Array to allow editing all values at once.
 */
const onEditAll = (): void => {
    props.equipmentData.forEach((data) => {
        editFields.value.push(data.id);
        editValues.value[data.id] = data.value;
    });
};

/**
 * Remove a single field from the Edit Array to show current value.
 */
const removeEditField = (dataId: number): void => {
    editFields.value.splice(
        editFields.value.findIndex((dataItem) => dataItem === dataId),
        1
    );
    delete editValues.value[dataId];
};

/**
 * Remove all fields from the Edit Array to cancel any input edits.
 */
const onCancelAll = (): void => {
    editFields.value = [];
};

/**
 * Save a single fields new value.
 */
const onSaveEditField = (dataId: number): void => {
    let formData: equipmentFormData[] = [
        {
            fieldId: dataId,
            value: editValues.value[dataId],
        },
    ];

    triggerSave(formData);
};

/**
 * Save any open field values.
 */
const onSaveAll = (): void => {
    let formData: equipmentFormData[] = [];

    editValues.value.forEach((value, key) => {
        formData.push({
            fieldId: key,
            value: value,
        });
    });

    triggerSave(formData);
};

/**
 * Send the save data.
 */
const triggerSave = (saveData: equipmentFormData[]) => {
    isLoading.value = true;
    const formData = useForm({
        saveData,
    });

    formData.put(
        route("customers.update-equipment-data", [
            customer.value.slug,
            props.equipment.cust_equip_id,
        ]),
        {
            onSuccess: () => {
                saveData.forEach((fieldData) => {
                    removeEditField(fieldData.fieldId);
                });
            },
            onFinish: () => (isLoading.value = false),
        }
    );
};
</script>

<template>
    <Card>
        <template #title>
            <div class="grid grid-cols-3">
                <div class="flex">
                    <RefreshButton
                        :only="['equipment-data']"
                        @loading-start="isLoading = true"
                        @loading-complete="isLoading = false"
                    />
                </div>
                <h3 class="text-center grow">{{ equipment.equip_name }}</h3>
            </div>
        </template>
        <Overlay :loading="isLoading">
            <div v-if="!equipmentData.length">
                <h4 class="text-center">
                    Looks like the Equipment Profile has not finished building
                    yet.
                </h4>
                <p class="text-center">Refresh to try again.</p>
            </div>
            <TableStacked :items="equipmentData" class="w-full">
                <template #index="{ rowData }">
                    {{ rowData.value.field_name }}:
                </template>
                <template #value="{ rowData }">
                    <EquipmentDataInput
                        v-if="editFields.includes(rowData.value.id)"
                        v-model="editValues[rowData.value.id]"
                        :data="rowData.value"
                        :equipment="equipment"
                        @close-edit="removeEditField"
                        @save-edit="onSaveEditField"
                    />
                    <EquipmentDataValue
                        v-else
                        :data="rowData.value"
                        @edit-field="addEditField"
                    />
                </template>
            </TableStacked>
            <div class="flex flex-row-reverse mt-2">
                <EditButton
                    v-if="!editFields.length && permissions.equipment.update"
                    text="Edit All"
                    size="small"
                    pill
                    @click="onEditAll"
                />
                <div v-if="editFields.length">
                    <BaseButton
                        text="Save All"
                        size="small"
                        pill
                        @click="onSaveAll"
                    />
                    <BaseButton
                        class="ms-1"
                        size="small"
                        text="Cancel All"
                        variant="danger"
                        pill
                        @click="onCancelAll"
                    />
                </div>
            </div>
        </Overlay>
    </Card>
</template>
