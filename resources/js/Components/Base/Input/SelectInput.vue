<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <select
            class="form-select"
            :class="{ 'is-valid' : isValid, 'is-invalid' : isInvalid }"
            v-model="value"
        >
            <option
                v-for="opt in optionList"
                :value="getValue(opt)"
            >{{ getText(opt) }}</option>
        </select>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
        >
            {{ errorMessage }}
        </span>
    </div>
</template>

<script setup lang="ts">
    import { toRef, computed }       from 'vue';
    import { useField }              from 'vee-validate';
    import type { optionListObject } from '@/Types';

    const props = defineProps<{
        id        : string;
        label?    : string;
        name      : string;
        optionList: string[] | number[] | optionListObject[];
    }>();

    const isValid = computed(() => {
        return meta.valid && meta.validated && !meta.pending;
    });

    const isInvalid = computed(() => {
        return !meta.valid && meta.validated && !meta.pending
    });

    const getValue = (opt:optionListObject | string | number) => {
        if(typeof opt === 'object') {
            return opt.value;
        }

        return opt;
    }

    const getText = (opt:optionListObject | string | number) => {
        if(typeof opt === 'object') {
            return opt.text;
        }

        return opt;
    }

    const nameRef = toRef(props, 'name');
    const { errorMessage, value, meta } = useField(nameRef);
</script>
