<template>
    <div>
        <InputGroup>
            <InputGroupAddon
                v-if="$slots['start-text']"
                class="border border-e-0 my-2"
            >
                <slot name="start-text" />
            </InputGroupAddon>
            <FloatLabel variant="on" class="my-2">
                <InputText
                    v-model="value"
                    class="p-2"
                    :type="type ?? 'text'"
                    :autofocus="focus"
                    :disabled="disabled"
                    :id="id"
                    :autocomplete="autocomplete ?? 'off'"
                    :class="borderType"
                    :variant="filled ? 'filled' : null"
                    :placeholder="placeholder"
                    fluid
                    @focus="hasFocus = true"
                    @blur="hasFocus = false"
                />
                <label :for="id">{{ label }}</label>
            </FloatLabel>
            <InputGroupAddon
                v-if="$slots['end-text']"
                class="border border-s-0 my-2"
            >
                <slot name="end-text" />
            </InputGroupAddon>
        </InputGroup>
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

<script setup lang="ts">
import {
    InputText,
    FloatLabel,
    Message,
    InputGroup,
    InputGroupAddon,
} from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    label?: string;
    name: string;
    autocomplete?: string;
    borderBottom?: boolean;
    disabled?: boolean;
    filled?: boolean;
    focus?: boolean;
    help?: string;
    type?: string;
    placeholder?: string;
}>();

const hasFocus = ref(false);

const borderType = computed(() =>
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
    value: Ref<string>;
} = useField(nameRef);
</script>

<style>
.p-inputtext::placeholder {
    color: transparent;
}

.p-inputtext:focus::placeholder {
    /* color: inherit; */
    /* @apply text-muted; */
    @apply text-muted-color;
}
</style>
