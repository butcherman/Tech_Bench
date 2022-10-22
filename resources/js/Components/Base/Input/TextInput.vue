<template>
    <div class="mb-3 form-floating">
        <input
            v-model="value"
            :id="id"
            :type="type ? type : 'text'"
            :placeholder="label"
            :disabled="disabled"
            class="form-control order-2"
            v-focus="focus"
            @change="$emit('change', value)"
        >
        <label :for="id">{{ label }}</label>
        <span class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { toRef } from 'vue';
    import { useField }           from 'vee-validate';

    defineEmits(['change']);

    const props = defineProps<{
        id       : string;
        name     : string;
        type    ?: string;
        label    : string;
        focus   ?: boolean;
        disabled?: boolean;
    }>();

    const nameRef = toRef(props, 'name');
    const { errorMessage, value } = useField(nameRef);
</script>
