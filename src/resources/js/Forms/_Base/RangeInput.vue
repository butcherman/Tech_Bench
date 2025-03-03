<script setup lang="ts">
import { Slider, Message } from "primevue";
import { ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    label: string;
    name: string;
    help?: string;
    min?: number;
    max?: number;
    valueText?: string;
}>();

const hasFocus = ref<boolean>(false);

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
    value: Ref<number>;
} = useField(nameRef);
</script>

<template>
    <div class="mb-3">
        <label :for="id" class="text-muted font-bold mb-2">
            {{ label }}
        </label>
        <Slider
            v-model="value as number"
            class="my-2"
            :id="id"
            :name="name"
            :min="min"
            :max="max"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
        />
        <div class="text-muted">
            <slot name="value-slot" :value="value">
                {{ valueText ?? "Value: " }}
                {{ value }}
            </slot>
        </div>
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
