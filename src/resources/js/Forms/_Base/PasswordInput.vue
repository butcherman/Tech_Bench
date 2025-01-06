<template>
    <FloatLabel variant="on" class="my-2">
        <Password
            v-model="value"
            class="p-2"
            type="password"
            :autofocus="focus"
            :id="id"
            :disabled="disabled"
            :autocomplete="autocomplete ?? 'off'"
            :class="borderType"
            :feedback="false"
            :variant="filled ? 'filled' : undefined"
            toggle-mask
            fluid
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
import { Password, FloatLabel, Message } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    label: string;
    name: string;
    borderBottom?: boolean;
    autocomplete?: string;
    help?: string;
    filled?: boolean;
    focus?: boolean;
    disabled?: boolean;
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
