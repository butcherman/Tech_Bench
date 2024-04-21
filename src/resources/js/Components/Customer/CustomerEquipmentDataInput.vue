<template>
    <input
        v-model="inputVal"
        :id="`field-id-${data.id}`"
        type="text"
        class="form-control equip-data-input"
        :class="{ 'is-invalid': !isValid }"
        :pattern="data.data_field_type.pattern!"
        title="You Suck"
        @blur="validateInput"
    />
    <span v-if="!isValid" class="text-danger">{{
        data.data_field_type.pattern_error
    }}</span>
</template>

<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
    data: customerEquipmentData;
}>();

const isValid = ref(true);
const inputVal = ref(props.data.value);

const validateInput = (e: FocusEvent) => {
    console.log(e);

    if (!(<HTMLInputElement>e.target)?.checkValidity()) {
        isValid.value = false;
    } else {
        isValid.value = true;
    }
};
</script>
