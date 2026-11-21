<script setup lang="ts">
import { useFieldArray } from "vee-validate";

const props = defineProps<{
    name: string;

    emptyText?: string;
}>();

const { remove, push, fields } = useFieldArray(props.name);

const onRemove = (index: number): void => {
    console.log("remove", index);
    remove(index);
};
</script>

<template>
    <div>
        <div v-if="!fields.length">
            <slot name="empty">
                <p class="text-muted">{{ emptyText }}</p>
            </slot>
        </div>
        <slot :onRemove :push :fields />
    </div>
</template>
