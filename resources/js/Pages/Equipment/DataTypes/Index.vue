<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Data Types</div>
                        <p class="text-center">
                            When equipment is assigned to a customer, the
                            following Data Types are available to gather
                            information for that equipment
                        </p>
                        <p class="text-center">
                            <strong>Note:</strong>  Data Types that are in use
                            cannot be deleted until they have been removed from
                            all Equipment Types
                        </p>
                        <hr />
                        <ul class="list-group">
                            <template v-for="data in dataList" :key="data.type_id">
                                <li class="list-group-item">
                                    {{ data.name }}
                                    <span class="float-end">
                                        <span
                                            v-if="!data.in_use"
                                            class="text-danger pointer mx-1"
                                            title="Delete"
                                            v-tooltip
                                            @click="verifyDelete(data)"
                                        >
                                            <fa-icon icon="trash-can" />
                                        </span>
                                        <Link
                                            v-else
                                            :href="$route('data_types.show', data.type_id)"
                                            class="text-info pointer mx-1"
                                            title="Show References"
                                            v-tooltip
                                        >
                                            <fa-icon icon="fa-brands fa-searchengin" />
                                        </Link>
                                        <span
                                            class="text-info pointer mx-1"
                                            title="Edit"
                                            v-tooltip
                                            @click="onEditClick(data)"
                                        >
                                            <fa-icon icon="edit" />
                                        </span>
                                    </span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create New Data Type</div>
                        <VueForm
                            ref="newTypeForm"
                            :validation-schema="validationSchema"
                            @submit="onNewSubmit"
                        >
                            <TextInput
                                id="new-name"
                                name="name"
                                label="Data Type Name"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
        <Modal ref="editTypeModal" title="Edit Customer Data Type">
            <VueForm
                ref="editTypeForm"
                :validation-schema="validationSchema"
                @submit="onEditSubmit"
            >
                <TextInput
                    id="new-name"
                    name="name"
                    label="Data Type Name"
                />
            </VueForm>
        </Modal>
    </div>
</template>

<script setup lang="ts">
    import App                   from '@/Layouts/app.vue';
    import Modal                 from '@/Components/Base/Modal/Modal.vue';
    import VueForm               from '@/Components/Base/VueForm.vue';
    import TextInput             from '@/Components/Base/Input/TextInput.vue';
    import { ref }               from 'vue';
    import { useForm }           from '@inertiajs/vue3';
    import { verifyModal }       from '@/Modules/verifyModal.module';
    import { router }           from '@inertiajs/vue3';
    import * as yup              from 'yup';
    import type { dataListType } from '@/Types';

    interface dataFormType {
        name: string;
    }

    defineProps<{
        dataList: dataListType[];
    }>();

    const $route = route;

    /**
     * Destroy Data
     */
    const verifyDelete = (data:dataListType) => {
        verifyModal('Are you sure?').then(res => {
            if(res)
            {
                router.delete(route('data_types.destroy', data.type_id));
            }
        });
    }

    /**
     * Create Data
     */
    const newTypeForm = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        name: yup.string().required('The Data Type must have a name'),
    }

    const onNewSubmit = (form:dataFormType) => {
        const formData = useForm(form);
        formData.post(route('data_types.store'), {
            onFinish: () => newTypeForm.value?.endSubmit(),
        });
    }

    /**
     * Edit Data
     */
    const editTypeModal = ref<InstanceType<typeof Modal>   | null>(null);
    const editTypeForm  = ref<InstanceType<typeof VueForm> | null>(null);
    const editId        = ref<number>();

    const onEditClick   = (data:dataListType) => {
        editTypeModal.value?.show();
        editTypeForm.value?.setFieldValue('name', data.name as never);
        editId.value = data.type_id;
    }

    const onEditSubmit = (form:dataFormType) => {
        const formData = useForm(form);
        formData.put(route('data_types.update', editId.value), {
            onFinish: () => {
                editTypeForm.value?.endSubmit();
                editTypeModal.value?.hide();
            },
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
