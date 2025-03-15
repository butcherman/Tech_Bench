<template>
    <div class="my-2 p-1 text-center">
        <label v-if="label" :for="id" class="font-bold text-muted">
            {{ label }}
        </label>
        <InputOtp
            v-model="value"
            class="flex justify-center"
            size="large"
            :id="id"
            :length="length ?? 4"
        >
            <template #default="{ attrs, events }">
                <input
                    class="border h-8 md:h-10 w-12 md:w-16 text-center rounded-lg"
                    type="text"
                    v-bind="attrs"
                    v-on="events"
                />
            </template>
        </InputOtp>
        <div v-if="hasError" class="text-danger text-sm text-center w-full">
            {{ errorMessage }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { InputOtp } from "primevue";
import { useField } from "vee-validate";
import { computed, toRef } from "vue";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    autocomplete?: string;
    disabled?: boolean;
    focus?: boolean;
    label?: string;
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
    value: Ref<"string" | boolean | undefined>;
} = useField(nameRef);
</script>
