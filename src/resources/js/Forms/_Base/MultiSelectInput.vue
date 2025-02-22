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
            severity="secondary"
            size="small"
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
    list: any[] | any;
    name: string;
    filter?: boolean;
    groupChildrenField?: string;
    groupTextField?: string;
    help?: string;
    label?: string;
    noChip?: boolean;
    placeholder?: string;
    textField?: string;
    valueField?: string;
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
