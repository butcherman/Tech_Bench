<template>
    <div class="mb-3">
        <label :for="id" class="form-label">{{ label }}: {{ value }}</label>
        <input
            v-model="value"
            :id="id"
            type="range"
            class="form-range"
            :min="min"
            :max="max"
        >
        <span class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { toRef }    from 'vue';
    import { useField } from 'vee-validate';
    import type { Ref } from 'vue';

    const props = defineProps<{
        id    : string;
        name  : string;
        label : string;
        min  ?: number;
        max  ?: number;
    }>();

    const nameRef = toRef(props, 'name');
    const { errorMessage, value }:
          { errorMessage:Ref<string>, value:Ref<string>} = useField(nameRef);
</script>
