<template>
    <Head title="Customer File Types" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer File Types</div>
                        <p class="text-center">
                            When uploading a file for a customer, it must be
                            tagged as one of the following file types.
                        </p>
                        <p class="text-center">
                            Select the file type to edit, or create a new file
                            type.
                        </p>
                        <Overlay :loading="loading">
                            <ul class="list-group">
                                <li
                                    v-for="type in fileTypes"
                                    :key="type.file_type_id"
                                    class="list-group-item text-center pointer"
                                >
                                    {{ type.description }}
                                    <span
                                        class="float-end text-danger mx-1"
                                        title="Delete File Type"
                                        @click.capture="removeType(type)"
                                        v-tooltip
                                    >
                                        <fa-icon icon="fa-trash-can" />
                                    </span>
                                    <span
                                        class="float-end text-warning mx-1"
                                        title="Edit File Type"
                                        @click.capture="openEditForm(type)"
                                        v-tooltip
                                    >
                                        <fa-icon icon="fa-edit" />
                                    </span>
                                </li>
                            </ul>
                        </Overlay>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create New File Type</div>
                        <VueForm
                            ref="newTypeForm"
                            :validation-schema="validationSchema"
                            @submit="onNewSubmit"
                        >
                            <TextInput
                                id="new-type"
                                name="description"
                                label="File Type Name"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
        <Modal ref="editTypeModal" title="Edit File Type">
            <VueForm
                ref="editTypeForm"
                :validation-schema="validationSchema"
                @submit="onEditSubmit"
            >
                <TextInput
                    id="new-type"
                    name="description"
                    label="File Type Name"
                />
            </VueForm>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import Overlay from "@/Components/Base/Overlay.vue";
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import Modal from "@/Components/Base/Modal/Modal.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { verifyModal } from "@/Modules/verifyModal.module";
import * as yup from "yup";

defineProps<{
    fileTypes: fileTypes[];
}>();

const loading = ref<boolean>(false);
const validationSchema = {
    description: yup.string().required().label("File Type Name"),
};

/**
 * New File Type
 */
const newTypeForm = ref<InstanceType<typeof VueForm> | null>(null);
const onNewSubmit = (form: fileTypes) => {
    const formData = useForm(form);
    formData.post(route("admin.cust.file-types.store"), {
        onFinish: () => newTypeForm.value?.endSubmit(),
    });
};

/**
 * Edit FIle Type
 */
const editTypeModal = ref<InstanceType<typeof Modal> | null>(null);
const editTypeForm = ref<InstanceType<typeof VueForm> | null>(null);
const editId = ref<number>();
const openEditForm = (type: fileTypes) => {
    editId.value = type.file_type_id;
    editTypeForm.value?.setFieldValue("description", type.description);
    editTypeModal.value?.show();
};
const onEditSubmit = (form: fileTypes) => {
    const formData = useForm(form);
    formData.put(route("admin.cust.file-types.update", editId.value), {
        onSuccess: () => editTypeModal.value?.hide(),
        onFinish: () => editTypeForm.value?.endSubmit(),
    });
};

/**
 * Delete file type
 */
const removeType = (type: fileTypes) => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            loading.value = true;
            router.delete(
                route("admin.cust.file-types.destroy", type.file_type_id),
                {
                    onFinish: () => (loading.value = false),
                }
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: App };
</script>
