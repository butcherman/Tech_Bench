<template>
    <div class="my-2">
        <v-select
            v-model="value"
            :id="id"
            :disabled="disabled"
            :error-messages="errorMessage"
            :label="label"
            :variant="inputVariant"
            :items="list"
            :item-title="textField"
            :item-value="valueField"
            :menu-props="{
                eager: true,
            }"
        />
    </div>
</template>

<script setup lang="ts">
import { computed, toRef } from "vue";
import { useField } from "vee-validate";

const props = defineProps<{
    id: string;
    name: string;
    label: string;
    list: any | any[];
    textField?: string;
    valueField?: string;
    disabled?: boolean;
    variant?:
        | "filled"
        | "underlined"
        | "outlined"
        | "plain"
        | "solo"
        | "solo-inverted"
        | "solo-filled"
        | undefined;
}>();

const inputVariant = computed(() => props.variant ?? "outlined");

const valueField = computed(() =>
    props.valueField ? props.valueField : "value"
);
const textField = computed(() => (props.textField ? props.textField : "text"));

// const isValid = computed<boolean>(() => {
//     return meta.valid && meta.validated && !meta.pending;
// });

// const isInvalid = computed<boolean>(() => {
//     return !meta.valid && meta.validated && !meta.pending;
// });

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>
