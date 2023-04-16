<template>
    <button
        class="float-end btn btn-info btn-pill btn-sm"
        title="New Contact"
        v-tooltip
        @click="newContactModal?.show()"
    >
        <fa-icon icon="fa-plus" />
        Add
    </button>
    <Modal ref="newContactModal" title="New Contact">
        <VueForm
            ref="newContactForm"
            :validation-schema="validationSchema"
            :initial-values="{ shared: false }"
            @submit="onSubmit"
        >
            <TextInput
                id="name"
                name="name"
                label="Contact Name"
            />
            <TextInput
                id="title"
                name="title"
                label="Title"
            />
            <TextInput
                id="email"
                name="email"
                label="Email Address"
                type="email"
            />
            <CheckboxSwitch
                v-show="allowShare"
                id="shared"
                name="shared"
                label="Shared Across All Linked Sited"
                class="my-2"
            />
        </VueForm>
    </Modal>
</template>

<script setup lang="ts">
    import Modal           from '@/Components/Base/Modal/Modal.vue';
    import VueForm         from '@/Components/Base/VueForm.vue';
    import CheckboxSwitch  from '@/Components/Base/Input/CheckboxSwitch.vue';
    import TextInput       from '@/Components/Base/Input/TextInput.vue';
    import { ref, inject } from 'vue';
    import { useForm }     from '@inertiajs/inertia-vue3';
    import * as yup        from 'yup';
    import type { Ref }    from 'vue';
    import type {
                  customerType,
                  voidFunctionType } from '@/Types';

    const allowShare        = inject<boolean>('allowShare');
    const custData          = inject<Ref<customerType>>('customer');
    const toggleLoad        = inject<voidFunctionType>('toggleLoad', () => {});

    const newContactModal   = ref<InstanceType<typeof Modal>   | null>(null);
    const newContactForm    = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema  = {
        shared: yup.boolean().nullable(),
        name  : yup.string().required().label('Contact Name'),
        title : yup.string().nullable().label('Contact Title'),
        email : yup.string().email().label('Email Address').nullable(),
    };

    /**
     * Submit the new equipment type
     */
    const onSubmit = (form) => {
        console.log(form);
        // toggleLoad();
        // //  Add customer ID to the form
        // form.cust_id = (custData?.value.cust_id as unknown) as string;

        // //  Set any undefined fields to blank strings
        // Object.keys(form).forEach(key => {
        //     if(form[key] === undefined)
        //     {
        //         form[key] = '';
        //     }
        // });

        // const formData = useForm(form);
        // formData.post(route('customers.equipment.store'), {
        //     preserveScroll: true,
        //     only          : ['equipment', 'flash'],
        //     onFinish      : () => {
        //         newEquipmentForm.value?.endSubmit();
        //         toggleLoad();
        //     },
        //     onSuccess     : () => {
        //         newEquipmentModal.value?.hide();
        //         newEquipmentForm.value?.resetForm();
        //         otherFields.value = undefined;
        //     }
        // });
    }
</script>
