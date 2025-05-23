<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import { Message } from "primevue";
import { ref, defineModel } from "vue";

defineEmits<{
    closeEdit: [number];
    saveEdit: [number];
}>();

defineProps<{
    data: customerEquipmentData;
    equipment: customerEquipment;
}>();

const inputValue = defineModel<string>();
const isValid = ref<boolean>(true);

/**
 * If a Regex patter is supplied for validation, validate the input against it.
 */
const validateInput = (e: FocusEvent): void => {
    if (!(<HTMLInputElement>e.target)?.checkValidity()) {
        isValid.value = false;
    } else {
        isValid.value = true;
    }
};
</script>

<template>
    <div class="flex flex-row">
        <div class="grow">
            <input
                v-model="inputValue"
                class="w-full border border-slate-400 rounded-lg px-2"
                :class="{ invalid: !isValid }"
                :pattern="data.data_field_type.pattern!"
                @blur="validateInput"
            />
            <Message
                v-if="!isValid"
                size="small"
                severity="error"
                variant="simple"
            >
                {{ data.data_field_type.pattern_error }}
            </Message>
        </div>
        <div class="ms-2">
            <BaseBadge
                class="mx-1"
                icon="floppy-disk"
                v-tooltip="'Save'"
                @click="$emit('saveEdit', data.id)"
            />
            <BaseBadge
                icon="xmark"
                variant="danger"
                v-tooltip.left="'Cancel'"
                @click="$emit('closeEdit', data.id)"
            />
        </div>
    </div>
</template>

<style lang="postcss" scoped>
.invalid {
    @apply border-red-500;
}
</style>
