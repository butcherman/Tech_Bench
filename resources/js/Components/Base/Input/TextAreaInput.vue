<template>
    <div class="mb-3">
        <label :for="id" class="form-label w-100">
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
        <MazTextarea
            v-model="(value as string)"
            :rows="getRows"
            :id="id"
            :disabled="disabled"
            :label="placeholder"
            :class="{ 'is-valid': isValid, is_invalid: isInvalid }"
            @change="$emit('change', value)"
        />
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
            >{{ errorMessage }}</span
        >
    </div>
</template>

<script setup lang="ts">
import MazTextarea from "maz-ui/components/MazTextarea";
import { toRef, computed } from "vue";
import { useField } from "vee-validate";
import { helpModal } from "@/Modules/helpModal.module";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    label: string;
    placeholder?: string;
    disabled?: boolean;
    help?: string;
    rows?: number;
}>();

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const getRows = computed<number>(() => {
    return props.rows !== undefined ? props.rows : 3;
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
