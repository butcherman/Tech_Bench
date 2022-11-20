<template>
    <div class="mb-3 form-floating">
        <input
            v-model="value"
            :id="id"
            :type="type ? type : 'text'"
            :placeholder="label"
            :disabled="disabled"
            class="form-control order-2"
            :class="{ 'is-valid' : isValid, 'is-invalid' : isInvalid }"
            v-focus="focus"
            @change="$emit('change', value)"
        >
        <label :for="id">{{ label }}</label>
        <span v-if="errorMessage && (meta.dirty || meta.touched)" class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { toRef, computed } from 'vue';
    import { useField }        from 'vee-validate';

    defineEmits(['change']);

    const props = defineProps<{
        id       : string;
        name     : string;
        type    ?: string;
        label    : string;
        focus   ?: boolean;
        disabled?: boolean;
    }>();

    const isValid = computed(() => {
        return meta.valid && meta.validated && !meta.pending;
    });

    const isInvalid = computed(() => {
        return !meta.valid && meta.validated && !meta.pending
    });

    const nameRef = toRef(props, 'name');
    const { errorMessage, value, meta } = useField(nameRef);
</script>
