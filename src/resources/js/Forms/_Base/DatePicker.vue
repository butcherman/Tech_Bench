<script setup lang="ts">
import { DatePicker, FloatLabel, Message, InputGroupAddon } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

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
}>();

const hasFocus = ref<boolean>(false);

const borderType = computed<string>(() =>
    props.borderBottom ? "border-b rounded-none" : "border"
);

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
    value: Ref<Date | undefined>;
} = useField(nameRef);
</script>

<template>
    <div>
        <FloatLabel variant="on" class="my-2">
            <DatePicker
                v-model="value"
                class="p-2"
                :autocomplete="autocomplete ?? 'off'"
                :autofocus="focus"
                :class="borderType"
                :disabled="disabled"
                :id="id"
                :placeholder="placeholder"
                :variant="filled ? 'filled' : null"
                fluid
                @focus="hasFocus = true"
                @blur="hasFocus = false"
            />
            <label :for="id">{{ label }}</label>
        </FloatLabel>
        <InputGroupAddon
            v-if="$slots['end-text']"
            class="border border-s-0 my-2 shadow-sm"
        >
            <slot name="end-text" />
        </InputGroupAddon>
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
