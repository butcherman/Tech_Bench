<template>
    <Head title="Logo" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Current Logo</div>
                        <Transition name="fade" mode="out-in">
                            <img
                                :src="logo"
                                alt="Tech Bench Logo"
                                class="img-fluid"
                                :key="logo"
                            />
                        </Transition>
                    </div>
                </div>
            </div>
            <div v-if="showForm" class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">New Logo</div>
                        <VueForm
                            ref="logoForm"
                            class="h-100"
                            :validation-schema="validationSchema"
                            @submit="onSubmit"
                        >
                            <DropzoneInput
                                name="logo"
                                input-text="Drag New Logo Here"
                                file-types="image/*"
                                :multiple="false"
                                :max-files="1"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App           from '@/Layouts/app.vue';
    import VueForm       from '@/Components/Base/VueForm.vue';
    import DropzoneInput from '@/Components/Base/Input/DropzoneInput.vue';
    import { ref }       from 'vue';
    import { useForm }   from '@inertiajs/vue3';
    import * as yup      from 'yup';

    defineProps<{
        logo: string;
    }>();
    const logoForm         = ref<InstanceType<typeof VueForm> | null>(null);
    const showForm         = ref(true);
    const validationSchema = {
        logo: yup.array().required(),
    }

    interface logoFormType {
        logo: {
            path: string;
            name: string;
            size: number;
            type: string;
        }[]
    }

    const onSubmit = (form:logoFormType) => {
        const formData = useForm({logo: form.logo[0]});
        formData.post(route('admin.set-logo'), {
            onFinish : () => logoForm.value?.endSubmit(),
            onSuccess: () => showForm.value = false
        })
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
