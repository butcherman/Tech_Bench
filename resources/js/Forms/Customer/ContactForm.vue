<template>
    <form
        ref="contactForm"
        class="vld-parent"
        @submit.prevent="onSubmit"
        novalidate
    >
        <Overlay :loading="loading">
            <TextInput
                id="name"
                name="name"
                label="Name"
                placeholder="Contact Name"
            />
            <TextInput
                id="title"
                name="title"
                label="Title"
                placeholder="Contact Title"
            />
            <TextInput
                id="email"
                name="email"
                label="Email"
                placeholder="Contact Email Address"
                type="email"
            />
            <CheckboxSwitch
                v-show="allowShare"
                id="shared"
                name="shared"
                label="Shared Across All Linked Sites"
                class="my-2"
            />
            <TextAreaInput
                id="note"
                name="note"
                label="Note"
                placeholder="Enter any necessary notes about this contact"
            />
            <fieldset>
                <legend>Phone Numbers</legend>
                <div
                    v-for="(field, index) in fields"
                    :key="field.key"
                    class="row my-0 py-0"
                >
                    <div class="col-sm-3 px-1">
                        <SelectInput
                            :id="`type-${index}`"
                            :name="`phones[${index}].type`"
                            :option-list="phoneTypes"
                        />
                    </div>
                    <div class="col-sm-5 px-1 py-0">
                        <PhoneNumberInput
                            :id="`number-${index}`"
                            :name="`phones[${index}].number`"
                            placeholder="number"
                        />
                    </div>
                    <div class="col-sm-4 px-1 py-0">
                        <div class="row p-0 m-0">
                            <div class="col-10 p-0 m-0">
                                <TextInput
                                    :id="`ext-${index}`"
                                    :name="`phones[${index}].ext`"
                                    placeholder="ext"
                                />
                            </div>
                            <div class="col-1 p-0 m-1">
                                <span
                                    class="text-danger pointer"
                                    title="Remove Row"
                                    @click="remove(index)"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-circle-xmark" />
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix mb-4">
                    <button class="btn btn-info btn-sm btn-pill float-end" @click="newPhone">
                        <fa-icon icon="plus" />
                        Add
                    </button>
                </div>
            </fieldset>
            <SubmitButton :submitted="loading" text="Submit" class="mt-auto" />
        </Overlay>
    </form>
</template>

<script setup lang="ts">
import TextInput from "@/Components/Base/Input/TextInput.vue";
import TextAreaInput from "@/Components/Base/Input/TextAreaInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import PhoneNumberInput from "@/Components/Base/Input/PhoneNumberInput.vue";
import Overlay from "@/Components/Base/Overlay.vue";
import SubmitButton from "@/Components/Base/Input/SubmitButton.vue";
import { ref, reactive, onMounted, inject } from "vue";
import {
    allowShareKey,
    customerKey,
    phoneTypesKey,
    toggleContactsLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import {
    useForm as useVeeForm,
    useIsFormTouched,
    useFieldArray,
} from "vee-validate";
import { object, string, boolean, array } from 'yup';
import type { ComputedRef, Ref } from "vue";
import type { customerType, phoneNumberType } from "@/Types";
import { useForm } from "@inertiajs/vue3";

const emit = defineEmits(['success']);
const props = defineProps<{
    contactData?: {};
}>();

const allowShare = inject(allowShareKey) as ComputedRef<boolean>;
const custData = inject(customerKey) as Ref<customerType>;
const toggleLoad = inject(toggleContactsLoadKey) as () => void;
const phoneTypes = inject(phoneTypesKey) as Ref<string[]>;
const loading = ref<boolean>(false);


/**
 * Push a new phone number to the phones array
 */
const newPhone = () => {
    push({
        type: 'Mobile',
        number: '',
        ext: '',
    });
}

/**
 * Initialize form
 */
const { handleSubmit, values, errors } = useVeeForm({
    initialValues: {},
    validationSchema: object({
        name: string().required(),
        title: string().nullable(),
        email: string().email().nullable(),
        shared: boolean().required(),
        note: string().nullable(),
        phones: array().nullable(),
    }),
});
const { remove, push, fields } = useFieldArray("phones");
onMounted(() => newPhone());

/**
 * Submit the new/updated contact
 */
const onSubmit = handleSubmit((form) => {
    form.cust_id = custData.value.cust_id;
    console.log(form);

    const formData = useForm(form);

    loading.value = true;
    if(props.contactData !== undefined)
    {
        console.log('update contact');
    }
    else
    {
        formData.post(route('customers.contacts.store'), {
            onFinish: () => {toggleLoad(); loading.value = false},
            onSuccess: () => emit('success'),
        });
    }
});
</script>

<style lang="scss">
form {
    fieldset {
        legend {
            font-size: 1em;
        }
    }
}
</style>
