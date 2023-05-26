<template>
    <VueForm
        ref="newEquipmentForm"
        :validation-schema="validationSchema"
        :initial-values="{ shared: false }"
        @submit="onSubmit"
    >
        <Overlay :loading="loading">
            <Field name="equip_id" v-slot="{ field, errorMessage }">
                <label for="select-equip">Equipment Type:</label>
                <select
                    id="select-equip"
                    class="form-select form-select-lg"
                    v-bind="field"
                    @change="populateForm"
                >
                    <optgroup
                        v-for="cat in equipList"
                        :key="cat.cat_id"
                        :label="cat.name"
                    >
                        <option
                            v-for="equip in cat.equipment_type"
                            :key="equip.equip_id"
                            :value="equip.equip_id"
                            :disabled="isFieldDisabled(equip)"
                        >
                            {{ equip.name }}
                        </option>
                    </optgroup>
                </select>
                <span class="text-danger">{{ errorMessage }}</span>
            </Field>
            <CheckboxSwitch
                v-show="allowShare"
                id="shared"
                name="shared"
                label="Shared Across All Linked Sited"
                class="my-2"
            />
            <template v-for="field in otherFields">
                <TextInput
                    :id="`field-id-${field.type_id}`"
                    :name="`fieldId-${field.type_id}`"
                    :label="field.name"
                />
            </template>
        </Overlay>
    </VueForm>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import VueForm from "@/Components/Base/VueForm.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import axios from "axios";
import { ref, inject, onMounted, ComputedRef } from "vue";
import { Field } from "vee-validate";
import { useForm } from "@inertiajs/vue3";
import {
    allowShareKey,
    customerKey,
    toggleEquipLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import { object, string, boolean } from "yup";
import type { Ref } from "vue";
import type {
    categoryList,
    customerEquipmentDataType,
    customerEquipmentType,
    customerType,
    dataListType,
    equipType,
    equipWithDataType,
    voidFunctionType,
} from "@/Types";

interface equipSelectBox {
    [key: string]: categoryList;
}

//  On Mount, get a full list of available equipment to assign to the customer
onMounted(() => getEquipment());

const props = defineProps<{
    existingEquipment: customerEquipmentType[];
}>();
const emit = defineEmits(["success"]);

const allowShare = inject(allowShareKey) as ComputedRef<boolean>;
const custData = inject(customerKey) as Ref<customerType>;
const toggleLoad = inject(toggleEquipLoadKey) as voidFunctionType;

const loading = ref<boolean>(false);
const newEquipmentForm = ref<InstanceType<typeof VueForm> | null>(null);
const otherFields = ref<dataListType[]>();
const validationSchema = object({
    equip_id: string().required().label("Equipment Type"),
    shared: boolean().nullable(),
});

/**
 * Get a full list of available equipment types
 */
const equipList = ref<equipSelectBox>();
const getEquipment = async () => {
    // if (equipList.value === undefined) {
    //     loading.value = true;
    //     await axios(route("equipment.get-all")).then((res) => {
    //         equipList.value = res.data;
    //     });
    //     loading.value = false;
    // }
};

/**
 * Determine if a field should be disabled based on if the equipment type
 * already exists or not
 */
const isFieldDisabled = (equip: equipType) => {
    let found = false;

    props.existingEquipment.forEach((item: customerEquipmentType) => {
        if (item.equip_id === equip.equip_id) {
            found = true;
        }
    });

    return found;
};

/**
 * Build the form based on the selected equipment type
 */
const populateForm = (event: Event) => {
    const equipId = (event.target as HTMLInputElement)
        .value as unknown as number;
    const equip = findEquipment(equipId);

    otherFields.value = equip?.data_field_type;
};

/**
 * Find the equipment based on the equipId
 */
const findEquipment = (equipId: number): equipWithDataType | undefined => {
    let selectedEquip;

    if (typeof equipList.value !== "undefined") {
        Object.values(equipList.value).forEach((cat) => {
            Object.values(cat.equipment_type).forEach((equip) => {
                if (equip.equip_id == equipId) {
                    selectedEquip = equip as equipWithDataType;
                }
            });
        });
    }

    return selectedEquip;
};

/**
 * Submit the new equipment type
 */
const onSubmit = (form: customerEquipmentDataType) => {
    toggleLoad();
    //  Add customer ID to the form
    form.cust_id = custData?.value.cust_id as unknown as string;

    //  Set any undefined fields to blank strings
    Object.keys(form).forEach((key) => {
        if (form[key] === undefined) {
            form[key] = "";
        }
    });

    const formData = useForm(form);
    formData.post(route("customers.equipment.store"), {
        preserveScroll: true,
        only: ["equipment", "flash"],
        onFinish: () => {
            newEquipmentForm.value?.endSubmit();
            toggleLoad();
        },
        onSuccess: () => {
            newEquipmentForm.value?.resetForm();
            otherFields.value = undefined;
            emit("success");
        },
    });
};
</script>
