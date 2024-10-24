<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
            <span
                v-if="help"
                title="What is this?"
                class="pointer pl-2 text-info float-end"
                @click.prevent="showHelp"
                v-tooltip
            >
                <fa-icon icon="fa-circle-question" />
            </span>
        </label>
        <div class="input-group">
            <slot name="start-group-text" />
            <input
                ref="inputField"
                v-model="value"
                :id="id"
                :type="type ? type : 'text'"
                :placeholder="placeholder"
                :disabled="disabled"
                :list="`datalist-${id}`"
                :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
                class="form-control"
                :autocomplete="autoCompleteValue"
                v-focus="focus"
                @change="$emit('change', value)"
                @focus="$emit('focus')"
            />
            <span
                v-if="type === 'password' && allowShowPass"
                class="password-toggler"
                title="Show/Hide Password"
                v-tooltip
                @mouseover="togglePassword(false)"
                @mouseleave="togglePassword(true)"
            >
                <fa-icon :icon="showIcon" />
            </span>
            <slot name="end-group-text" />
        </div>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
            v-html="upperFirst(errorMessage)"
        />
        <datalist :id="`datalist-${id}`">
            <template v-for="item in datalist" :key="item">
                <option :value="item" />
            </template>
        </datalist>
    </div>
</template>

<script setup lang="ts">
import okModal from "@/Modules/okModal";
import { ref, toRef, computed } from "vue";
import { useField } from "vee-validate";
import { upperFirst } from "lodash";

defineEmits(["change", "focus"]);

const props = defineProps<{
    id: string;
    name: string;
    type?: string;
    label?: string;
    placeholder?: string;
    focus?: boolean;
    disabled?: boolean;
    help?: string;
    datalist?: string[];
    autocomplete?: "on" | "off";
    allowShowPass?: boolean;
}>();

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const autoCompleteValue = computed(() => props.autocomplete || "off");

const showHelp = () => {
    okModal(props.help!!);
};

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);

/**
 * Show/Hide Password Field
 */
const inputField = ref<InstanceType<typeof HTMLElement> | null>(null);
const showPassword = ref(false);
const showIcon = computed(() => (showPassword.value ? "eye-slash" : "eye"));
const togglePassword = (showPas: boolean) => {
    showPassword.value = showPas;
    if (showPassword.value) {
        inputField.value?.setAttribute("type", "password");
    } else {
        inputField.value?.setAttribute("type", "text");
    }
};
</script>

<style scoped lang="scss">
label {
    font-weight: bold;
}

.input-group {
    position: relative;
    .password-toggler {
        opacity: 0.6;
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 1000;
    }
}
</style>
