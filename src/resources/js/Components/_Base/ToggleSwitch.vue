<script setup lang="ts">
import { ToggleSwitch } from "primevue";

defineProps<{
    id: string;
    name: string;
    center?: boolean;
    disabled?: boolean;
    help?: string;
    label?: string;
    reverse?: boolean;
}>();

const value = defineModel<boolean>();
</script>

<template>
    <div class="my-2" :class="{ 'flex justify-center': center }">
        <div
            class="flex gap-2"
            :class="{ 'flex-row-reverse text-end': reverse }"
        >
            <ToggleSwitch
                v-model="value"
                :disabled="disabled"
                pt:handle:class="text-white"
            />
            <div class="grow">
                <label class="text-muted">{{ label }}</label>
            </div>
        </div>
    </div>
</template>
