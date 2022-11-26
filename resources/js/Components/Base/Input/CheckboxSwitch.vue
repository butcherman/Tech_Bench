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
            <slot name="label">
                {{ label }}
            </slot>
        </label>
        <span class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { toRef } from 'vue';
    import { useField }           from 'vee-validate';

    const props = defineProps<{
        id    : string;
        name  : string;
        label?: string;
    }>();

    const nameRef = toRef(props, 'name');
    const { errorMessage, value } = useField(nameRef);
</script>
