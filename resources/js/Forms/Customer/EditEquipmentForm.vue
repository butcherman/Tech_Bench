<template>
    <VueForm
        ref="editEquipmentForm"
        :validation-schema="{}"
        :initial-values="initialValues"
        @submit="onSubmit"
    >
        <CheckboxSwitch
            v-if="allowShare"
            id="shared"
            name="shared"
            label="Shared Across All Linked Sited"
            class="my-2"
        />
        <template
            v-for="field in equipData.customer_equipment_data"
            :key="field.id"
        >
            <TextInput
                :id="`field-id-${field.id}`"
                :name="`fieldId-${field.id}`"
                :label="field.field_name.toString()"
            />
        </template>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import { ref, computed, inject, defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    allowShareKey,
    toggleEquipLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import type {
    voidFunctionType,
    customerEquipmentType,
    customerEquipmentDataType,
} from "@/Types";
import type { Ref } from "vue";

const props = defineProps<{
    equipData: customerEquipmentType;
}>();
const toggleLoad = inject(toggleEquipLoadKey) as voidFunctionType;
const allowShare = inject(allowShareKey) as Ref<boolean>;
const emit = defineEmits(["success"]);

const editEquipmentForm = ref<InstanceType<typeof VueForm> | null>(null);
const initialValues = computed(() => {
    let valueObj: customerEquipmentDataType = {
        shared: props.equipData.shared,
    };
    for (let field of props.equipData.customer_equipment_data) {
        valueObj[`fieldId-${field.id}`] = field.value;
    }

    return valueObj;
});

const onSubmit = (form: customerEquipmentDataType) => {
    toggleLoad();
    const formData = useForm(form);
    formData.put(
        route("customers.equipment.update", props.equipData.cust_equip_id),
        {
            preserveScroll: true,
            only: ["equipment", "flash"],
            onSuccess: () => emit("success"),
            onFinish: () => {
                editEquipmentForm.value?.endSubmit();
                toggleLoad();
            },
        }
    );
};
</script>
