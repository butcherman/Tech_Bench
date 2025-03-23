<script setup lang="ts">
import { Select, Message, FloatLabel } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    list: any[];
    name: string;
    disabled?: boolean;
    groupChildrenField?: string;
    groupTextField?: string;
    help?: string;
    label?: string;
    textField?: string;
    valueField?: string;
}>();

const hasFocus = ref<boolean>(false);

const optionLabel = computed(() => {
    if (typeof props.list[0] === "string") {
        return undefined;
    }

    return props.textField ?? "text";
});

const optionValue = computed(() => {
    if (typeof props.list[0] === "string") {
        return undefined;
    }

    return props.valueField ?? "text";
});

/*
|-------------------------------------------------------------------------------
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string>;
} = useField(nameRef);
</script>

<template>
    <FloatLabel variant="on" class="w-full my-2">
        <Select
            v-model="value"
            class="w-full border"
            :id="id"
            :options="list"
            :option-label="optionLabel"
            :option-value="optionValue"
            :option-group-label="groupTextField"
            :option-group-children="groupChildrenField"
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
