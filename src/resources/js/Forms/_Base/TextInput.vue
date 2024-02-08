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
                v-model="value"
                :id="id"
                :type="type ? type : 'text'"
                :placeholder="placeholder"
                :disabled="disabled"
                :list="`datalist-${id}`"
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
            v-html="upperFirst(errorMessage)"
        />
        <datalist :id="`datalist-${id}`">
            <template v-for="item in datalist" :key="item">
                <option :value="item" />
            </template>
        </datalist>
    </div>
</template>

<script setup lang="ts">
import okModal from "@/Modules/okModal";
import { toRef, computed } from "vue";
import { useField } from "vee-validate";
import { upperFirst } from "lodash";

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
    datalist?: string[];
}>();

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const showHelp = () => {
    okModal(props.help!!);
};

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>

<style>
label {
    font-weight: bold;
}
</style>
