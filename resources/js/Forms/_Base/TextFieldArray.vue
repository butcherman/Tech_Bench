<template>
    <div>
        <div v-for="(field, index) in fields" :key="field.key">
            <TextInput
                :id="`${name}-${index}`"
                :name="`${name}[${index}]`"
                :label="label"
                :placeholder="placeholder"
            >
                <template #start-group-text>
                    <slot name="start-group-text" />
                </template>
                <template #end-group-text>
                    <span
                        class="input-group-text pointer"
                        title="Remove this item"
                        v-tooltip
                        @click="remove(index)"
                    >
                        <fa-icon icon="xmark" class="text-danger" />
                    </span>
                </template>
            </TextInput>
        </div>
        <AddButton class="btn-pill btn-sm float-end" @click="push('')" />
    </div>
</template>

<script setup lang="ts">
import TextInput from "./TextInput.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { useFieldArray } from "vee-validate";

const props = defineProps<{
    name: string;
    label?: string;
    placeholder?: string;
}>();

const { remove, push, fields } = useFieldArray(props.name);
</script>
