<template>
    <div class="mb-3">
        <label v-if="label" :for="id"><strong>{{ label }}:</strong></label>
        <select class="form-select form-select-lg" v-model="value">
            <option
                v-for="opt in optionList"
                :value="getValue(opt)"
            >{{ getText(opt) }}</option>
        </select>
        <span class="text-danger">{{ errorMessage }}</span>
    </div>
</template>

<script setup lang="ts">
    import { toRef }                 from 'vue';
    import { useField }              from 'vee-validate';
    import type { optionListObject } from '@/Types';

    const props = defineProps<{
        id        : string;
        label?    : string;
        name      : string;
        optionList: string[] | number[] | optionListObject[];
    }>();

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

    const nameRef                 = toRef(props, 'name');
    const { errorMessage, value } = useField(nameRef);
</script>
