<template>
    <Head title="Logo" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Current Logo</div>
                        <Transition name="fade" mode="out-in">
                            <img :src="logo" alt="Tech Bench Logo" class="img-fluid" :key="logo" />
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
                            :validation-schema="{}"
                            @submit="onSubmit"
                        >
                            <DropzoneInput
                                name="logo"
                                input-text="Drag New Logo Here"
                                file-types="image/*"
                                :multiple="false"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import VueForm from '@/Components/Base/VueForm.vue';
    import DropzoneInput from '@/Components/Base/Input/DropzoneInput.vue';
    import { ref, reactive, onMounted } from 'vue';
    import { useForm } from '@inertiajs/inertia-vue3';

    defineProps<{
        logo: string;
    }>();
    const logoForm = ref<InstanceType<typeof VueForm> | null>(null);
    const showForm = ref(true);


    // const filesDroped => (files) => {
    //     console.log(files);
    // }


    const onSubmit = (form) => {
        const formData = useForm({logo: form.logo[0]});
        formData.post(route('admin.set-logo'), {
            onFinish: () => {
                logoForm.value?.endSubmit();
                showForm.value = false;
            },
        })
    }

</script>
