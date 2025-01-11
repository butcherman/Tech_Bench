<template>
    <FloatLabel variant="on" class="my-2 w-full">
        <MultiSelect
            v-model="value"
            class="w-full border"
            :display="noChip ? undefined : 'chip'"
            :filter="filter"
            :id="id"
            :options="list"
            :option-label="textField ?? 'label'"
            :option-value="valueField ?? 'value'"
            :option-group-label="groupTextField"
            :option-group-children="groupChildrenField"
            :placeholder="placeholder"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
        />
        <label :for="id">{{ label }}</label>
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
import { MultiSelect, FloatLabel, Message } from "primevue";
import { ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    list: any[] | any;
    label?: string;
    textField?: string;
    valueField?: string;
    groupTextField?: string;
    groupChildrenField?: string;
    noChip?: boolean;
    placeholder?: string;
    help?: string;
    filter?: boolean;
}>();

const hasFocus = ref(false);

const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string>;
} = useField(nameRef);
</script>
