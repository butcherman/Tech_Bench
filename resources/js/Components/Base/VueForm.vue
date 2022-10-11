<template>
    <form class="vld-parent" @submit="onSubmit" novalidate>
        <Loading :active="isSubmitting" :is-full-page="false" />
        <slot />
        <slot name="submit">
            <SubmitButton :submitted="isSubmitting" :text="submitText ? submitText : 'Submit'" />
        </slot>
    </form>
</template>

<script setup lang="ts">
    import SubmitButton               from '@/Components/Base/Input/SubmitButton.vue';
    import Loading                    from 'vue3-loading-overlay';
    import { ref }                    from 'vue';
    import { useForm as useVeeForm }  from 'vee-validate';

    const emit  = defineEmits(['submit']);
    const props = defineProps<{
        validationSchema: object;
        initialValues  ?: object;
        submitText     ?: string;
    }>();

    const isSubmitting     = ref(false);
    const { handleSubmit } = useVeeForm({
        validationSchema: props.validationSchema,
        initialValues   : props.initialValues ? props.initialValues : {},
    });

    const onSubmit = handleSubmit(form => {
        isSubmitting.value = true;
        emit('submit', form);
    });

    function _endSubmit()
    {
        isSubmitting.value = false;
    }

    defineExpose({ endSubmit : _endSubmit });
</script>

<style scoped lang="scss">
    @import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';
</style>
