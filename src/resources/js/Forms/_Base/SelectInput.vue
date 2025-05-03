<script setup lang="ts">
import { Select, Message, FloatLabel } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    list: any[];
    name: string;
    borderBottom?: boolean;
    disabled?: boolean;
    groupChildrenField?: string;
    groupTextField?: string;
    help?: string;
    label?: string;
    textField?: string;
    valueField?: string;
}>();

/*
|-------------------------------------------------------------------------------
| Styling Data
|-------------------------------------------------------------------------------
*/
const borderType = computed<string>(() =>
    props.borderBottom ? "border-b rounded-none" : "border"
);

/*
|-------------------------------------------------------------------------------
| Input Focus State
|-------------------------------------------------------------------------------
*/
const hasFocus = ref<boolean>(false);

const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};

const onBlur = (): void => {
    hasFocus.value = false;
    emit("blur");
};

const optionLabel = computed<string | undefined>(() => {
    if (typeof props.list[0] === "string") {
        return undefined;
    }

    return props.textField ?? "text";
});

const optionValue = computed<string | undefined>(() => {
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
            class="w-full"
            :class="borderType"
            :disabled="disabled"
            :id="id"
            :options="list"
            :option-label="optionLabel"
            :option-value="optionValue"
            :option-group-label="groupTextField"
            :option-group-children="groupChildrenField"
            @focus="onFocus"
            @blur="onBlur"
        >
            <template #option="slotProps">
                <slot name="option" v-bind="slotProps" />
            </template>
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

<style lang="postcss">
.p-select-option-label {
    @apply ms-4;
}
</style>
