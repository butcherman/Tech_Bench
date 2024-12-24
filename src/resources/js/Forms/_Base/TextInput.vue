<template>
    <div class="my-2">
        <v-text-field
            v-model="value"
            :id="id"
            :autocomplete="autocomplete ?? 'off'"
            :autofocus="focus"
            :disabled="disabled"
            :error-messages="errorMessage"
            :hint="hint"
            :label="label"
            :persistent-hint="persistentHint"
            :placeholder="placeholder"
            :type="type ?? 'text'"
            :variant="inputVariant"
        />
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useField } from "vee-validate";
import { toRef } from "vue";

const props = defineProps<{
    id: string;
    label: string;
    name: string;
    autocomplete?: string;
    disabled?: boolean;
    focus?: boolean;
    hint?: string;
    persistentHint?: boolean;
    placeholder?: string;
    type?: string;
    variant?:
        | "filled"
        | "underlined"
        | "outlined"
        | "plain"
        | "solo"
        | "solo-inverted"
        | "solo-filled"
        | undefined;
}>();

const inputVariant = computed(() => props.variant ?? "outlined");

/*
|---------------------------------------------------------------------------
| Vee-Validate
|---------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
