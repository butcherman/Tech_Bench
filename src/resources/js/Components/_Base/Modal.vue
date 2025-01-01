<template>
    <v-dialog
        v-model="isOpen"
        width="auto"
        min-width="300"
        :persistent="preventClose"
        @after-enter="$emit('shown')"
        @after-leave="$emit('hidden')"
    >
        <Card :title="title" class="relative">
            <span
                v-if="!preventClose"
                class="absolute top-0 right-1 pointer text-muted hover:text-red-500"
                @click="hide()"
            >
                <v-icon icon="xmark" size="small" />
            </span>
            <div class="mb-5">
                <slot />
            </div>
            <div v-if="$slots.actions" class="border-t">
                <slot name="actions" />
            </div>
        </Card>
    </v-dialog>
</template>

<script setup lang="ts">
import Card from "./Card.vue";
import { ref } from "vue";

defineEmits<{
    shown: null;
    hidden: null;
}>();

defineProps<{
    title?: string;
    preventClose?: boolean;
}>();

const isOpen = ref(false);

const show = () => {
    isOpen.value = true;
};

const hide = () => {
    isOpen.value = false;
};

defineExpose({ show, hide });
</script>
