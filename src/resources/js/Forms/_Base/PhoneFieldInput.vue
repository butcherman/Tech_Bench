<script setup lang="ts">
import PhoneNumberInput from "./PhoneNumberInput.vue";
import SelectInput from "./SelectInput.vue";
import TextInput from "./TextInput.vue";
import { ref } from "vue";
import { Message } from "primevue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

defineProps<{
    id: string;
    name: string;
    autocomplete?: string;
    borderBottom?: boolean;
    disabled?: boolean;
    focus?: boolean;
    help?: string;
    label?: string;
    placeholder?: string;
    phoneTypes: phoneType[];
}>();

/*
|-------------------------------------------------------------------------------
| Input Focus State
|-------------------------------------------------------------------------------
*/
const hasFocus = ref<boolean>(false);

const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};

const onBlur = (): void => {
    hasFocus.value = false;
    emit("blur");
};
</script>

<template>
    <div>
        <fieldset :id="id" class="flex">
            <legend class="font-bold text-muted block">{{ label }}</legend>
            <SelectInput
                class="basis-36"
                label="Phone Type"
                text-field="description"
                value-field="description"
                :border-bottom="borderBottom"
                :disabled="disabled"
                :id="`${id}-type`"
                :list="phoneTypes"
                :name="`${name}.type`"
                @focus="onFocus"
                @blur="onBlur"
            />
            <PhoneNumberInput
                class="grow mx-1"
                label="Phone Number"
                :autocomplete="autocomplete ?? 'off'"
                :autofocus="focus"
                :border-bottom="borderBottom"
                :disabled="disabled"
                :id="`${id}-number`"
                :name="`${name}.number`"
                :placeholder="placeholder"
                @focus="onFocus"
                @blur="onBlur"
            />
            <TextInput
                class="basis-28"
                label="Extension"
                :border-bottom="borderBottom"
                :disabled="disabled"
                :id="`${id}-ext`"
                :name="`${name}.ext`"
                @focus="onFocus"
                @blur="onBlur"
            />
        </fieldset>
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
