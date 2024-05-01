<template>
    <div
        class="form-check form-switch"
        :class="{ 'form-check-inline': inline }"
    >
        <input
            :id="id"
            v-model="value"
            class="form-check-input"
            type="checkbox"
            :name="name"
            :value="true"
            :unchecked-value="false"
            :disabled="disabled"
            @change="$emit('change')"
        />
        <label :for="id" class="form-check-label">
            <slot name="label">
                {{ label }}
            </slot>
        </label>
        <span class="text-danger">{{ errorMessage }}</span>
        <span
            v-if="help"
            title="What is this?"
            class="pointer pl-2 text-info float-end ms-3"
            @click.prevent="showHelp"
            v-tooltip
        >
            <fa-icon icon="fa-circle-question" />
        </span>
    </div>
</template>

<script setup lang="ts">
import okModal from "@/Modules/okModal";
import { toRef } from "vue";
import { useField } from "vee-validate";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    disabled?: boolean;
    help?: string;
    inline?: boolean;
}>();

const showHelp = () => {
    okModal(props.help!!);
};

const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
