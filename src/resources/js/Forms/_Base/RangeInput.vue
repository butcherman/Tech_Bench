<template>
    <div class="my-2">
        <v-slider
            v-model="value"
            :id="id"
            :name="name"
            :error-messages="errorMessage"
            :label="label"
            :min="min"
            :max="max"
            step="1"
        >
            <template #append>{{ formatValue }}</template>
        </v-slider>
    </div>
</template>

<script setup lang="ts">
import prettyBytes from "pretty-bytes";
import { computed, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    label: string;
    min?: number;
    max?: number;
    format?: "prettybytes";
}>();

const formatValue = computed<unknown>(() => {
    switch (props.format) {
        case "prettybytes":
            return prettyBytes(Number(value.value));
        default:
            return value.value;
    }
});

const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<number>;
} = useField(nameRef);
</script>
