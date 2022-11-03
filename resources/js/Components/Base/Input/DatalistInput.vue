<template>
    <div class="mb-3 form-floating input-group">
        <input
            v-model="value"
            :id="id"
            :placeholder="label"
            :disabled="disabled"
            class="form-control order-2"
            :list="`datalist-${id}`"
            v-focus="focus"
            @change="$emit('change', value)"
        >
        <label :for="id">{{ label }}</label>
        <span class="text-danger">{{ errorMessage }}</span>
        <datalist :id="`datalist-${id}`">
            <template v-for="item in datalist">
                <option :value="item" />
            </template>
        </datalist>
    </div>
</template>

<script setup lang="ts">
    import { toRef }    from 'vue';
    import { useField } from 'vee-validate';

    defineEmits(['change']);

    const props = defineProps<{
        id       : string;
        name     : string;
        label    : string;
        datalist : string[];
        focus   ?: boolean;
        disabled?: boolean;
    }>();

    const nameRef = toRef(props, 'name');
    const { errorMessage, value } = useField(nameRef);
</script>
