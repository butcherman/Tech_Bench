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
                                paramName="logo"
                                ref="fileUpload"
                                class="mb-2"
                                :accepted-files="['image/*']"
                                :multiple="false"
                                :max-files="1"
                                :upload-url="$route('admin.set-logo')"
                                required
                                @success="uploadComplete"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import VueForm from "@/Components/Base/VueForm.vue";
import DropzoneInput from "@/Components/Base/Input/DropzoneInput.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    logo: string;
}>();
const fileUpload = ref<InstanceType<typeof DropzoneInput> | null>(null);
const logoForm = ref<InstanceType<typeof VueForm> | null>(null);
const showForm = ref(true);
const validationSchema = {}

const $route = route;

interface logoForm {
    logo: {
        path: string;
        name: string;
        size: number;
        type: string;
    }[];
}

const onSubmit = () => {
    if (fileUpload.value?.validate()) {
        fileUpload.value?.process();
    }
    else
    {
        logoForm.value?.endSubmit();
    }
};

const uploadComplete = () => {
    logoForm.value?.endSubmit();
    fileUpload.value?.reset();
    router.reload();
};
</script>

<script lang="ts">
export default { layout: App };
</script>
