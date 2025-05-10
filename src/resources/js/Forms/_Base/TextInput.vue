<script setup lang="ts">
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import {
    FloatLabel,
    InputGroup,
    InputGroupAddon,
    InputText,
    Message,
} from "primevue";

import type { Ref } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    autocomplete?: string;
    borderBottom?: boolean;
    disabled?: boolean;
    filled?: boolean;
    focus?: boolean;
    help?: string;
    label?: string;
    placeholder?: string;
    type?: string;
}>();

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
    <div>
        <InputGroup>
            <InputGroupAddon
                v-if="$slots['start-text']"
                class="border border-e-0 my-2 shadow-sm"
            >
                <slot name="start-text" />
            </InputGroupAddon>
            <FloatLabel variant="on" class="my-2">
                <InputText
                    v-model="value"
                    class="p-2"
                    :autocomplete="autocomplete ?? 'off'"
                    :autofocus="focus"
                    :class="borderType"
                    :disabled="disabled"
                    :id="id"
                    :placeholder="placeholder"
                    :type="type ?? 'text'"
                    :variant="filled ? 'filled' : null"
                    fluid
                    @focus="onFocus"
                    @blur="onBlur"
                />
                <label :for="id">{{ label }}</label>
            </FloatLabel>
            <InputGroupAddon
                v-if="$slots['end-text']"
                class="border border-s-0 my-2 shadow-sm"
            >
                <slot name="end-text" />
            </InputGroupAddon>
        </InputGroup>
        <Message size="small" severity="error" variant="simple">
            <span v-html="errorMessage" />
        </Message>
        <Message
            v-if="hasFocus"
            size="small"
            severity="secondary"
            variant="simple"
        >
            {{ help }}
        </Message>
    </div>
</template>

<style lang="postcss">
.p-inputtext::placeholder {
    color: transparent;
}

.p-inputtext:focus::placeholder {
    @apply text-muted-color;
}
</style>
