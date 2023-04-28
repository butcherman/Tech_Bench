<template>
    <div class="mb-3">
        <label :for="id" class="form-label w-100">
            {{ label }}:
            <span
                v-if="help"
                title="What is this?"
                class="pointer pl-2 text-primary float-end"
                @click.prevent="showHelp"
                v-tooltip
            >
                <fa-icon icon="fa-circle-question" />
            </span>
        </label>
        <input
            v-model="value"
            :id="id"
            :type="type ? type : 'text'"
            :placeholder="placeholder"
            :disabled="disabled"
            class="form-control order-2"
            :class="{ 'is-valid' : isValid, 'is-invalid' : isInvalid }"
            v-focus="focus"
            @change="$emit('change', value)"
        >
        <span v-if="errorMessage && (meta.dirty || meta.touched)" class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { toRef, computed } from 'vue';
    import { useField }        from 'vee-validate';
    import { helpModal }          from '@/Modules/helpModal.module';

    defineEmits(['change']);

    const props = defineProps<{
        id          : string;
        name        : string;
        type       ?: string;
        label       : string;
        placeholder?: string;
        focus      ?: boolean;
        disabled   ?: boolean;
        help       ?: string;
    }>();

    const isValid = computed(() => {
        return meta.valid && meta.validated && !meta.pending;
    });

    const isInvalid = computed(() => {
        return !meta.valid && meta.validated && !meta.pending
    });

    const nameRef = toRef(props, 'name');
    const { errorMessage, value, meta } = useField(nameRef);

    const showHelp = () => {
        helpModal('help', {
            title: 'What is this?',
        });
    }
</script>

<style>
    label {
        font-weight: bold
    }
</style>
