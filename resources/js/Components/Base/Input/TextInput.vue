<template>
    <div class="mb-3 form-floating">
        <input
            v-model="value"
            :id="id"
            :type="type ? type : 'text'"
            :placeholder="label"
            class="form-control order-2"
            v-focus="focus"
        >
        <label :for="id">{{ label }}</label>
        <span class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { defineProps, toRef } from 'vue';
    import { useField }           from 'vee-validate';
    import type { Ref }           from 'vue';

    const props = defineProps<{
        id    : string;
        name  : string;
        type ?: string;
        label : string;
        focus?: boolean;
    }>();

    const nameRef = toRef(props, 'name');
    const { errorMessage, value }:
          { errorMessage:Ref<string>, value:Ref<string>} = useField(nameRef);
</script>
