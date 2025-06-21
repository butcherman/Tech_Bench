<script setup lang="ts">
import { ToggleSwitch, Message } from "primevue";
import { toRef, ref, computed } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    center?: boolean;
    disabled?: boolean;
    help?: string;
    label?: string;
}>();

/*
|-------------------------------------------------------------------------------
| Input Focus State
|-------------------------------------------------------------------------------
*/
const hasFocus = ref<boolean>(false);
const showHelp = ref<boolean>(false);
const helpTooltip = computed(() =>
    showHelp.value ? "Hide Help" : "Show Help"
);

const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};

const onBlur = (): void => {
    hasFocus.value = false;
    emit("blur");
};

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
    value: Ref<string | boolean>;
} = useField(nameRef);
</script>

<template>
    <div class="my-2" :class="{ 'flex justify-center': center }">
        <div class="flex gap-2">
            <ToggleSwitch
                v-model="value"
                :input-id="id"
                :invalid="errorMessage ? true : false"
                :disabled="disabled"
                pt:handle:class="text-white"
                @focus="onFocus"
                @blur="onBlur"
            />
            <div class="grow">
                <label class="text-muted">{{ label }}</label>
                <Message size="small" severity="error" variant="simple">
                    <span v-html="errorMessage" />
                </Message>
                <Message
                    v-show="showHelp"
                    size="small"
                    severity="secondary"
                    variant="simple"
                >
                    {{ help }}
                </Message>
            </div>
            <div v-if="help" v-tooltip="helpTooltip">
                <BaseBadge icon="question" @click="showHelp = !showHelp" />
            </div>
        </div>
    </div>
</template>
