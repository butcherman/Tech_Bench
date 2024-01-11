<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
            <span
                v-if="help"
                title="What is this?"
                class="pointer pl-2 text-info float-end"
                @click.prevent="showHelp"
                v-tooltip
            >
                <fa-icon icon="fa-circle-question" />
            </span>
        </label>
        <div class="input-group">
            <slot name="start-group-text" />
            <input
                v-model="formattedValue"
                :id="id"
                type="tel"
                :placeholder="placeholder ? placeholder : '+1 (XXX) XXX-XXXX'"
                :disabled="disabled"
                class="form-control"
                :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
                v-focus="focus"
                @change="$emit('change', value)"
            />
            <slot name="end-group-text" />
        </div>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
        >
            {{ upperFirst(errorMessage) }}
        </span>
    </div>
</template>

<script setup lang="ts">
import okModal from "@/Modules/ok";
import { ref, toRef, computed, watch, onMounted } from "vue";
import { useField } from "vee-validate";
import { upperFirst } from "lodash";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    placeholder?: string;
    focus?: boolean;
    disabled?: boolean;
    help?: string;
}>();

const formattedValue = ref<string | null>(null);

/**
 * When a valid phone number in inserted, format to a readable number
 * yet keep the value as only the 10 digit number
 */
watch(formattedValue, (newVal) => {
    let cleaned = `${newVal}`.replace(/\D/g, "");
    let parts = cleaned.match(/^(1|)?([2-9]{1}\d{2})(\d{3})(\d{4})$/);

    if (parts) {
        formattedValue.value = `+1 (${parts[2]}) ${parts[3]}-${parts[4]}`;
    }

    setValue(cleaned.replace(/^1/, ""));
});

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending && meta.dirty;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const showHelp = () => {
    okModal(props.help!!);
};

const nameRef = toRef(props, "name");
const { errorMessage, value, meta, setValue } = useField(nameRef);

onMounted(() => {
    if (typeof value.value === "string") {
        formattedValue.value = value.value;
    }
});
</script>

<style>
label {
    font-weight: bold;
}
</style>
