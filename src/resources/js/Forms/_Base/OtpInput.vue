<template>
    <div class="my-2 p-1 text-center">
        <label :for="id" class="font-bold">{{ label }}</label>
        <InputOtp
            v-model="value"
            :id="id"
            size="large"
            class="flex justify-center"
            :length="length ?? 4"
        >
            <template #default="{ attrs, events }">
                <input
                    type="text"
                    v-bind="attrs"
                    v-on="events"
                    class="border h-10 w-16 text-center rounded-lg"
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
    value: Ref<"string" | boolean | undefined>;
} = useField(nameRef);
</script>
