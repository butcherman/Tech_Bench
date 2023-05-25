<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
            <span
                v-if="help"
                title="What is this?"
                class="pointer pl-2 text-primary float-end"
                @click.prevent="showHelp"
                v-tooltip
            >
                <fa-icon icon="fa-circle-question" />
            </span>
        </label>
        <MazPhoneNumberInput
            v-model="(value as string)"
            :id="id"
            :disabled="disabled"
            class="w-100"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
            v-focus="focus"
            @change="$emit('change', value)"
            no-country-selector
            size="sm"
        />
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
            >{{ errorMessage }}</span
        >
    </div>
</template>

<script setup lang="ts">
import MazPhoneNumberInput from "maz-ui/components/MazPhoneNumberInput";
import { toRef, computed } from "vue";
import { useField } from "vee-validate";
import { helpModal } from "@/Modules/helpModal.module";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    type?: string;
    label?: string;
    placeholder?: string;
    focus?: boolean;
    disabled?: boolean;
    help?: string;
}>();

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);

const showHelp = ():void => {
    helpModal("help", {
        title: "What is this?",
    });
};
</script>

<style>
label {
    font-weight: bold;
}
</style>
