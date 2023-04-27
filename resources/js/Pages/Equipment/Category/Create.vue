<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create Category</div>
                        <VueForm
                            ref="categoryForm"
                            :validation-schema="validationSchema"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="cat-name"
                                name="name"
                                label="Category Name"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App         from '@/Layouts/app.vue';
    import VueForm     from '@/Components/Base/VueForm.vue';
    import TextInput   from '@/Components/Base/Input/TextInput.vue';
    import { ref }     from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import * as yup    from 'yup';

    interface catFormType {
        name: string;
    }

    const categoryForm     = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        name: yup.string().required('Category Name is required'),
    }
    const onSubmit         = (form:catFormType) => {
        const formData = useForm(form);
        formData.post(route('equipment_categories.store'), {
            onFinish: () => categoryForm.value?.endSubmit(),
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
