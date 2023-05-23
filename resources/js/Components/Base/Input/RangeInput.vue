<template>
    <div class="mb-3">
        <label :for="id" class="form-label">
            {{ label }}: {{ formatValue }}
        </label>
        <input
            v-model="value"
            :id="id"
            type="range"
            class="form-range"
            :min="min"
            :max="max"
        />
        <span class="text-danger">
            {{ errorMessage }}
        </span>
    </div>
</template>

<script setup lang="ts">
import prettyBytes from "pretty-bytes";
import { toRef, computed } from "vue";
import { useField } from "vee-validate";

const props = defineProps<{
    id: string;
    name: string;
    label: string;
    min?: number;
    max?: number;
    format?: undefined | "prettybytes";
}>();

const formatValue = computed(() => {
    switch (props.format) {
        case "prettybytes":
            return prettyBytes(Number(value.value));
        default:
            return value.value;
    }
});

const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
