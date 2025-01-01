<template>
    <v-radio-group
        v-model="value"
        :label="label"
        :error-messages="errorMessage"
        :hint="help"
    >
        <template v-for="item in list">
            <v-radio :value="item[valueData]">
                <template #label>
                    <slot name="label" :item="item">
                        {{ item[textData] }}
                    </slot>
                </template>
            </v-radio>
        </template>
    </v-radio-group>
</template>

<script setup lang="ts">
import { computed, toRef } from "vue";
import { useField } from "vee-validate";

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    list: any[];
    help?: string;
    disabled?: boolean;
    valueField?: string;
    textField?: string;
}>();

const valueData = computed(() => props.valueField ?? "value");
const textData = computed(() => props.textField ?? "text");

/*
|---------------------------------------------------------------------------
| Vee-Validate
|---------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
