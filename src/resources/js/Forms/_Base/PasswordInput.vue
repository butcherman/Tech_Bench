<template>
    <div>
        <v-text-field
            v-model="value"
            :id="id"
            :autocomplete="autocomplete ?? 'off'"
            :disabled="disabled"
            :error-messages="errorMessage"
            :hint="hint"
            :label="label"
            :persistent-hint="persistentHint"
            :placeholder="placeholder"
            :type="visibleIcon ? 'text' : 'password'"
            :variant="inputVariant"
        >
            <template #append-inner>
                <span
                    @click="visibleIcon = !visibleIcon"
                    class="cursor-pointer text-xs"
                >
                    <v-icon :icon="visibleIcon ? 'fa-eye-slash' : 'fa-eye'" />
                </span>
            </template>
        </v-text-field>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import { useField } from "vee-validate";
import { toRef } from "vue";

const props = defineProps<{
    id: string;
    label: string;
    name: string;
    autocomplete?: string;
    disabled?: boolean;
    hint?: string;
    persistentHint?: boolean;
    placeholder?: string;
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
const visibleIcon = ref(false);

/*
|---------------------------------------------------------------------------
| Vee-Validate
|---------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
