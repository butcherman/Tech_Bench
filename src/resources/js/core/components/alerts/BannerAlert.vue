<script setup lang="ts">
import { computed } from "vue";
import { useVariantHelper } from "@/core/composables/variantHelper";

defineEmits<{
    dismiss: [];
}>();

const props = defineProps<{
    dismissable?: boolean;
    text: string;
    variant: VariantType;
}>();

const { getLightVariantClass, getVariantIcon } = useVariantHelper();

const bgClass = computed(() => getLightVariantClass(props.variant));
const icon = computed(() => getVariantIcon(props.variant));
</script>

<template>
    <div
        class="flex py-2 px-4 rounded-lg justify-between opacity-90"
        :class="bgClass"
    >
        <div><fa-icon :icon="icon" /></div>
        <div>{{ text }}</div>
        <div>
            <fa-icon
                v-if="dismissable"
                class="pointer"
                icon="xmark"
                @click="$emit('dismiss')"
            />
            <fa-icon v-else :icon="icon" />
        </div>
    </div>
</template>
