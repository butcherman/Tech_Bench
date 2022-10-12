<template>
    <div class="form-check form-switch">
        <input
            :id="id"
            v-model="value"
            class="form-check-input"
            type="checkbox"
            :value="true"
            :unchecked-value="false"
        />
        <label :for="id" class="form-check-label">
            {{ label }}
        </label>
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
        label : string;
    }>();

    const nameRef = toRef(props, 'name');
    const { errorMessage, value } = useField(nameRef);
</script>
