<template>
    <div>
        <span
            v-if="help"
            title="What is this?"
            class="pointer pl-2 text-info float-end"
            @click.prevent="showHelp"
            v-tooltip
        >
            <fa-icon icon="fa-circle-question" />
        </span>
        <div
            v-for="(item, index) in list"
            class="form-check"
            :class="{ 'form-check-inline': inline }"
        >
            <input
                v-model="value"
                class="form-check-input"
                type="radio"
                :name="name"
                :id="`radio-group-${name}-${index}`"
                :value="item.value"
                :disabled="disabled"
                @change="$emit('change', value)"
            />
            <label
                :for="`radio-group-${name}-${index}`"
                class="form-check-label"
            >
                {{ item.text }}
            </label>
        </div>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
            v-html="upperFirst(errorMessage)"
        />
    </div>
</template>

<script setup lang="ts">
import ok from "@/Modules/ok";
import { toRef } from "vue";
import { useField } from "vee-validate";
import { upperFirst } from "lodash";

interface radioGroupList {
    text: string;
    value: string;
}

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    list: radioGroupList[];
    inline?: boolean;
    help?: string;
    disabled?: boolean;
}>();

const showHelp = () => {
    ok(props.help!!);
};

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>
