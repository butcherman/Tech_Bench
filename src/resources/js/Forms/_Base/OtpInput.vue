<template>
    <div>
        <label :for="id">{{ label }}</label>
        <v-otp-input
            v-model="value"
            :id="id"
            :autocomplete="autocomplete ?? 'off'"
            :disabled="disabled"
            :error="hasError"
            :label="label"
            :length="length ?? 4"
            :autofocus="focus"
        />
        <div class="text-center text-error">{{ errorMessage }}</div>
    </div>
</template>

<script setup lang="ts">
import { useField } from "vee-validate";
import { computed, toRef } from "vue";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    label?: string;
    name: string;
    autocomplete?: string;
    disabled?: boolean;
    focus?: boolean;
    length?: number;
}>();

const hasError = computed(() => (errorMessage.value ? true : false));

/*
|---------------------------------------------------------------------------
| Vee-Validate
|---------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<number>;
} = useField(nameRef);
</script>
