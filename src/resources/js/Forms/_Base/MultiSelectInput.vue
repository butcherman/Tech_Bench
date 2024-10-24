<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <Multiselect
            ref="formMultiSelect"
            v-model="value"
            :id="id"
            :options="selectList"
            :mode="mode"
            :groups="groups"
            :searchable="searchable"
            :valueProp="valueField"
            :label="textField"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
            :close-on-select="closeOnSelect"
        >
            <template #beforelist v-if="allowSelectAll">
                <ul class="multiselect-options">
                    <li class="multiselect-option" @click="selectAll">
                        Select All
                    </li>
                </ul>
            </template>
        </Multiselect>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
        >
            {{ errorMessage }}
        </span>
    </div>
</template>

<script setup lang="ts">
import Multiselect from "@vueform/multiselect";
import { ref, computed, toRef } from "vue";
import { useField } from "vee-validate";
import "@vueform/multiselect/themes/default.css";

const props = defineProps<{
    id: string;
    name: string;
    list: any[] | any;
    mode: "multiple" | "tags" | "single" | undefined;
    label?: string;
    groups?: boolean;
    textField?: string;
    valueField?: string;
    placeholder?: string;
    searchable?: boolean;
    allowNull?: boolean;
    allowSelectAll?: boolean;
}>();

const formMultiSelect = ref<InstanceType<typeof Multiselect> | null>(null);

const selectList = computed(() => {
    let newList = [...props.list];

    if (props.allowNull) {
        if (props.textField && props.valueField) {
            newList.unshift({
                [props.textField]: null,
                [props.valueField]: null,
            });
        } else {
            newList.unshift(null);
        }
    }

    return newList;
});

const closeOnSelect = computed(() =>
    props.mode === "multiple" || props.mode === "tags" ? false : true
);

const valueField = computed(() =>
    props.valueField ? props.valueField : "value"
);
const textField = computed(() => (props.textField ? props.textField : "text"));

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const selectAll = () => {
    formMultiSelect.value?.selectAll();
    formMultiSelect.value?.close();
};

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>
