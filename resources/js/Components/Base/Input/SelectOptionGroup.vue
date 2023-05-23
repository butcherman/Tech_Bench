<template>
    <div class="mb-3">
        <label v-if="label" :for="id">{{ label }}:</label>
        <select class="form-select form-select-lg" v-model="value">
            <optgroup
                v-for="(group, key) in optionList"
                :label="key.toString()"
                :key="key"
            >
                <option
                    v-for="(opt, label) in group"
                    :value="label"
                    v-html="opt"
                ></option>
            </optgroup>
        </select>
        <span class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
import { toRef } from "vue";
import { useField } from "vee-validate";

const props = defineProps<{
    id: string;
    label?: string;
    name: string;
    optionList: {
        [key: string]: { [key: string]: string };
    };
}>();

const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
