<template>
    <FloatLabel variant="on" class="w-full my-2">
        <Select
            v-model="value"
            :id="id"
            :options="list"
            :option-label="textField ?? 'text'"
            :option-value="valueField ?? 'value'"
            :option-group-label="groupTextField"
            :option-group-children="groupChildrenField"
            class="w-full border"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
        >
        </Select>
        <label :for="id" class="text-muted">{{ label }}</label>
        <Message size="small" severity="error" variant="simple">
            {{ errorMessage }}
        </Message>
        <Message
            v-if="hasFocus"
            size="small"
            severity="secondary"
            variant="simple"
        >
            {{ help }}
        </Message>
    </FloatLabel>
</template>

<script setup lang="ts">
import { Select, Message, FloatLabel } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    list: any[];
    textField?: string;
    valueField?: string;
    groupTextField?: string;
    groupChildrenField?: string;
    disabled?: boolean;
    help?: string;
}>();

const hasFocus = ref(false);

const valueField = computed(() =>
    props.valueField ? props.valueField : "value"
);
const textField = computed(() => (props.textField ? props.textField : "text"));

const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string>;
} = useField(nameRef);
</script>
