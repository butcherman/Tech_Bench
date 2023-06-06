<template>
    <VueForm
        ref="newEquipmentForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        @submit="onSubmit"
    >
        <Field
            name="select_equipment"
            class="my-2"
            v-slot="{ field, errorMessage }"
        >
            <label for="select-equip">Equipment Type:</label>
            <select
                id="select-equip"
                v-bind="field"
                class="form-select form-select-lg"
                @change="populateForm(field.value)"
            >
                <optgroup
                    v-for="category in equipTypes"
                    :key="category.cat_id"
                    :label="category.name"
                >
                    <option
                        v-for="equipment in category.equipment_type"
                        :key="equipment.equip_id"
                        :value="equipment"
                        :disabled="isFieldDisabled(equipment)"
                    >
                        {{ equipment.name }}
                    </option>
                </optgroup>
            </select>
            <span class="text-danger">{{ errorMessage }}</span>
        </Field>
        <div id="other-fields-wrapper">
            <template v-for="field in otherFields">
                <TextInput
                    :id="`field-id-${field.type_id}`"
                    :name="`fieldId-${field.type_id}`"
                    :label="field.name"
                />
            </template>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import { ref } from "vue";
import { Field } from "vee-validate";
import {
    customer,
    equipment,
    equipTypes,
    toggleEquipLoad,
} from "@/State/Customer/CustomerState";
import { gsap } from "gsap";
import { useForm } from "@inertiajs/vue3";
import { object, number, boolean } from "yup";

const emit = defineEmits(["success"]);
const newEquipmentForm = ref<InstanceType<typeof VueForm> | null>(null);
const otherFields = ref<dataList[]>();
const validationSchema = object({
    cust_id: number().required(),
    equip_id: number().required(),
    shared: boolean().required(),
});
const initialValues = {
    cust_id: customer.value?.cust_id,
    equip_id: null,
    shared: false,
};

const onSubmit = (form: { [key: string]: string }) => {
    toggleEquipLoad();
    delete form.select_equipment;

    //  Set any undefined data as a blank string
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
            toggleEquipLoad();
        },
        onSuccess: () => {
            newEquipmentForm.value?.resetForm();
            otherFields.value = [];
            emit("success");
        },
    });
};

/**
 * If the customer already has equipment assigned, it cannot be selected
 */
const isFieldDisabled = (fieldEquip: customerEquipment) => {
    let found: boolean = false;
    if (equipment.value?.length) {
        equipment.value.forEach((equip: customerEquipment) => {
            if (equip.equip_id === fieldEquip.equip_id) {
                found = true;
            }
        });
    }

    return found;
};

/**
 * Populate the remaining fields in the form
 */
const populateForm = (equip: equipWithData) => {
    newEquipmentForm.value?.setFieldValue("equip_id", equip.equip_id);
    onLeave(document.getElementById("other-fields-wrapper"));
    otherFields.value = equip.data_field_type;
    onEnter(document.getElementById("other-fields-wrapper"));
};

/**
 * Animation Styles
 */
const onEnter = (el: Element | null) => {
    gsap.from(el, {
        opacity: 0,
        delay: 0.8,
    });
};

const onLeave = (el: Element | null) => {
    gsap.to(el, {
        opacity: 0,
        delay: 0.7,
    });
};
</script>
