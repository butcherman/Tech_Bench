<template>
    <Overlay :loading="loading">
        <div v-if="!equipmentData.length" class="text-center">
            <p>
                Looks like we have not finished building the Equipment Profile
                yet.
            </p>
            <p>Please Refresh and try again.</p>
            <RefreshButton :only="['equipment-data']" />
        </div>
        <div class="table-responsive">
            <table class="table table-sm">
                <tbody>
                    <tr
                        v-for="data in sortDataObject(
                            equipmentData,
                            'asc',
                            'order'
                        )"
                        :key="data.id"
                    >
                        <th class="text-end">{{ data.field_name }}:</th>
                        <td
                            :class="{
                                'mask-field':
                                    data.data_field_type.masked && data.value,
                            }"
                        >
                            <CustomerEquipmentDataInput
                                v-if="
                                    editFields.includes(data.id) &&
                                    permissions.equipment.update
                                "
                                :data="data"
                            />
                            <CustomerEquipmentDataValue v-else :data="data" />
                        </td>
                        <td>
                            <span v-if="editFields.includes(data.id)">
                                <span
                                    class="badge rounded-pill pointer bg-danger float-end"
                                    title="Cancel Edit"
                                    v-tooltip
                                    @click="cancelEdit(data.id)"
                                >
                                    <fa-icon icon="xmark" />
                                </span>
                                <span
                                    v-if="permissions.equipment.update"
                                    class="badge rounded-pill pointer bg-primary float-start"
                                    title="Save"
                                    v-tooltip
                                    @click="saveField(data.id)"
                                >
                                    <fa-icon icon="floppy-disk" />
                                </span>
                            </span>
                            <span v-else>
                                <ClipboardCopy
                                    v-if="
                                        data.data_field_type.allow_copy &&
                                        data.value
                                    "
                                    :value="data.value"
                                    class="float-md-start"
                                />
                                <EditBadge
                                    v-if="permissions.equipment.update"
                                    class="float-md-end"
                                    @click="editFields.push(data.id)"
                                />
                            </span>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <EditButton
                                v-if="permissions.equipment.update"
                                class="float-end"
                                text="Edit All"
                                small
                                pill
                                @click="onEditAll"
                            />
                            <button
                                v-if="
                                    editFields.length &&
                                    permissions.equipment.update
                                "
                                class="btn btn-primary btn-sm rounded-5 float-end"
                                @click="saveAllFields"
                            >
                                <fa-icon icon="floppy-disk" />
                                Save All
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </Overlay>
</template>

<script setup lang="ts">
import EditButton from "../_Base/Buttons/EditButton.vue";
import EditBadge from "../_Base/Badges/EditBadge.vue";
import CustomerEquipmentDataValue from "./CustomerEquipmentDataValue.vue";
import CustomerEquipmentDataInput from "./CustomerEquipmentDataInput.vue";
import ClipboardCopy from "@/Components/_Base/Badges/ClipboardCopy.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import { ref } from "vue";
import { sortDataObject } from "@/Modules/SortDataObject.module";
import { useForm } from "@inertiajs/vue3";
import { customer, permissions } from "@/State/CustomerState";

interface equipmentFormData {
    fieldId: number;
    value: string;
}

const props = defineProps<{
    equipmentData: customerEquipmentData[];
}>();

const loading = ref(false);

/**
 * List of fields that are being edited
 */
const editFields = ref<number[]>([]);

/**
 * Open all fields as inputs to be edited
 */
const onEditAll = () => {
    props.equipmentData.forEach((data) => editFields.value.push(data.id));
};

/**
 * Cancel one specific field being edited
 */
const cancelEdit = (id: number) => {
    editFields.value.splice(
        editFields.value.findIndex((itemId) => itemId === id),
        1
    );
};

/**
 * Save the data for one specific field
 */
const saveField = (fieldId: number) => {
    let form = [
        {
            fieldId: fieldId,
            value: getFieldValue(fieldId),
        },
    ];

    saveFormData(form);
};

/**
 * Save all modified fields
 */
const saveAllFields = () => {
    let form: equipmentFormData[] = [];
    editFields.value.forEach((field) => {
        form.push({
            fieldId: field,
            value: getFieldValue(field),
        });
    });

    saveFormData(form);
};

const getFieldValue = (fieldId: number) => {
    return (<HTMLInputElement>document.getElementById(`field-id-${fieldId}`))
        .value;
};

/**
 * Submit the form
 */
const saveFormData = (form: equipmentFormData[]) => {
    const formData = useForm({ saveData: form });
    loading.value = true;

    formData.put(
        route("customers.update-equipment-data", customer.value.slug),
        {
            onFinish: () => (loading.value = false),
            onSuccess: () => form.forEach((item) => cancelEdit(item.fieldId)),
        }
    );
};
</script>

<style lang="scss">
table {
    tbody {
        tr {
            th {
                width: 40%;
            }
        }
    }
}

.mask-text {
    display: none;
}

.mask-field {
    .mask-text {
        display: inline;
    }
    .mask-value {
        display: none;
    }

    &:hover {
        .mask-text {
            display: none;
        }
        .mask-value {
            display: inline;
        }
    }
}
</style>
