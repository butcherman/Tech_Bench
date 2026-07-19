<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    placeholder?: string;
    inputStyle?: "filled" | "standard" | "outlined";
}>();

/**
 * Variant styling for the input
 */
const styleClass = computed(() => {
    switch (props.inputStyle) {
        case "standard":
            return "form-input-standard";
        case "filled":
            return "form-input-filled";
        default:
            return "form-input-outlined";
    }
});
</script>

<template>
    <div class="flex my-2">
        <!-- <div>append front</div> -->
        <div class="grow">
            <div class="relative form-input-base" :class="styleClass">
                <input
                    type="text"
                    :id="id"
                    class="block peer"
                    :placeholder="placeholder"
                />
                <label :for="id">
                    {{ label }}
                </label>
            </div>
        </div>
        <!-- <div>append end</div> -->
    </div>
</template>
