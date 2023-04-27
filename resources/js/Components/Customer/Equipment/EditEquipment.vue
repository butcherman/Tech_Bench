<template>
    <button
        v-if="permission?.equipment.update"
        class="btn btn-warning mx-1 float-end"
        @click="editEquipmentModal?.show()"
    >
        <fa-icon icon="edit" />
        Edit
    </button>
    <Modal
        ref="editEquipmentModal"
        :title="`Edit ${equipData.name}`"
    >
        <VueForm
            ref="editEquipmentForm"
            :validation-schema="{}"
            :initial-values="initialValues"
            @submit="onSubmit"
        >
            <CheckboxSwitch
                v-if="allowShare"
                id="shared"
                name="shared"
                label="Shared Across All Linked Sited"
                class="my-2"
            />
            <template
                v-for="field in equipData.customer_equipment_data"
                :key="field.id"
            >
                <TextInput
                    :id="`field-id-${field.id}`"
                    :name="`fieldId-${field.id}`"
                    :label="field.field_name.toString()"
                />
            </template>
        </VueForm>
    </Modal>
</template>

<script setup lang="ts">
    import Modal                              from '@/Components/Base/Modal/Modal.vue';
    import VueForm                            from '@/Components/Base/VueForm.vue';
    import TextInput                          from '@/Components/Base/Input/TextInput.vue';
    import CheckboxSwitch                     from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref, computed, inject }          from 'vue';
    import { useForm }                        from '@inertiajs/vue3';
    import type { voidFunctionType,
                  customerEquipmentType,
                  customerEquipmentDataType,
                  customerPermissionType}     from '@/Types';

    const allowShare = inject('allowShare');
    const toggleLoad = inject<voidFunctionType>('toggleLoad', () => {});
    const permission = inject<customerPermissionType>('permission');
    const props      = defineProps<{
        equipData: customerEquipmentType;
    }>();

    const editEquipmentModal = ref<InstanceType<typeof Modal>   | null>(null);
    const editEquipmentForm  = ref<InstanceType<typeof VueForm> | null>(null);
    const initialValues      = computed(() => {
        let valueObj:customerEquipmentDataType = {
            shared: props.equipData.shared,
        }
        for(let field of props.equipData.customer_equipment_data)
        {
            valueObj[`fieldId-${field.id}`] = field.value;
        }

        return valueObj;
    });

    const onSubmit = (form:customerEquipmentDataType) => {
        toggleLoad();
        const formData = useForm(form);
        formData.put(route('customers.equipment.update', props.equipData.cust_equip_id), {
            preserveScroll: true,
            only          : ['equipment', 'flash'],
            onSuccess     : () => editEquipmentModal.value?.hide(),
            onFinish      : () => {
                editEquipmentForm.value?.endSubmit();
                toggleLoad();
            },
        });
    }
</script>
