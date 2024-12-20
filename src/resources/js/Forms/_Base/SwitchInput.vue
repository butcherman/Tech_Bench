<template>
    <div :class="{ 'justify-items-center': center }">
        <v-switch
            v-model="value"
            color="primary"
            :id="id"
            :name="name"
            :label="label"
            :disabled="disabled"
            :error-messages="errorMessage"
            :false-value="false"
            :true-value="true"
            :inline="inline"
        />
    </div>
</template>

<script setup lang="ts">
import { toRef } from "vue";
import { useField } from "vee-validate";

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    disabled?: boolean;
    help?: string;
    inline?: boolean;
    center?: boolean;
}>();

/*
|-------------------------------------------------------------------------------
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>
