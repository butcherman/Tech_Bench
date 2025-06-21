<script setup lang="ts" generic="T">
import { MultiSelect, Message, FloatLabel } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    list: T[];
    borderless?: boolean;
    disabled?: boolean;
    filter?: boolean;
    groupChildrenField?: string;
    groupTextField?: string;
    help?: string;
    label?: string;
    placeholder?: string;
    textField?: string;
    type?: string;
    valueField?: string;
}>();

/**
 * Auto Hide the placeholder when not in focus to support Floating Label.
 */
const inputPlaceholder = computed<string>(() =>
    props.placeholder && (hasFocus.value || !props.label)
        ? props.placeholder
        : ""
);

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
    value: Ref<string | undefined>;
} = useField(nameRef);
</script>

<template>
    <div class="my-2">
        <FloatLabel variant="on">
            <MultiSelect
                v-model="value"
                class="w-full"
                display="chip"
                :class="{
                    'border-hidden!': borderless,
                }"
                :disabled="disabled"
                :filter="filter"
                :input-id="id"
                :invalid="errorMessage ? true : false"
                :options="list"
                :option-label="optionLabel"
                :option-value="optionValue"
                :option-group-label="groupTextField"
                :option-group-children="groupChildrenField"
                :placeholder="inputPlaceholder"
                :pt="{
                    pcChip: {
                        root: 'bg-green-200! rounded-lg!',
                    },
                }"
                @focus="onFocus"
                @blur="onBlur"
            >
                <template #option="slotProps">
                    <slot name="option" v-bind="slotProps" />
                </template>
            </MultiSelect>
            <label :for="id">{{ label }}</label>
        </FloatLabel>
        <Message size="small" severity="error" variant="simple">
            <span v-html="errorMessage" />
        </Message>
        <Message
            v-show="hasFocus"
            size="small"
            severity="secondary"
            variant="simple"
        >
            {{ help }}
        </Message>
    </div>
</template>
