<template>
    <v-dialog width="auto" min-width="300" v-model="isOpen">
        <Card :title="title" class="relative">
            <span
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
import { computed, ref } from "vue";

const props = defineProps<{
    title?: string;
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
