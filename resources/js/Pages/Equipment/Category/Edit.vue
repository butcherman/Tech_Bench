<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Edit Category</div>
                        <VueForm
                            ref="categoryForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
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
    import { useForm } from '@inertiajs/inertia-vue3';
    import * as yup    from 'yup';

    interface catFormType {
        name: string;
    }

    const props = defineProps<{
        category: {
            cat_id: number;
            name  : string;
        }
    }>();

    const categoryForm     = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        name: yup.string().required('Category Name is required'),
    }
    const initialValues    = {
        name  : props.category.name,
        cat_id: props.category.cat_id,
    }
    const onSubmit         = (form:catFormType) => {
        const formData = useForm(form);
        formData.put(route('equipment_categories.update', props.category.cat_id), {
            onFinish: () => categoryForm.value?.endSubmit(),
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
