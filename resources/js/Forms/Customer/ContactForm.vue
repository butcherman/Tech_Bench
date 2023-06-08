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
                            :option-list="
                                phoneTypes.map((i: phoneNumber) => i['description'])
                            "
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
                    <button
                        class="btn btn-info btn-sm btn-pill float-end"
                        @click="newPhone"
                    >
                        <fa-icon icon="plus" />
                        Add
                    </button>
                </div>
            </fieldset>
            <SubmitButton
                :submitted="loading"
                :text="submitText"
                class="mt-auto"
            />
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
import { ref, onMounted, watch, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useForm as useVeeForm, useFieldArray } from "vee-validate";
import { object, string, boolean, array } from "yup";
import {
    customer,
    toggleContLoad,
    allowShare,
    phoneTypes,
} from "@/State/Customer/CustomerState";

const emit = defineEmits(["success"]);
const props = defineProps<{
    contactData?: customerContact;
}>();

const submitText = computed(() =>
    props.contactData === undefined ? "Add Contact" : "Update Contact"
);
const loading = ref<boolean>(false);

/**
 * When a new contact is clicked, load that data into the edit form
 */
watch(props, () => {
    resetForm();

    if (props.contactData) {
        setValues({
            name: props.contactData.name!!,
            title: props.contactData.title!!,
            email: props.contactData.email!!,
            shared: props.contactData.shared!!,
            note: props.contactData.note!!,
        });

        props.contactData?.customer_contact_phone.forEach(
            (item: contactPhone) => {
                push({
                    type: item.phone_number_type.description,
                    number: item.phone_number,
                    ext: item.extension,
                    id: item.id,
                });
            }
        );

        newPhone();
    }
});

/**
 * Push a new phone number to the phones array
 */
const newPhone = () => {
    push({
        type: "Mobile",
        number: "",
        ext: "",
    });
};

/**
 * Initialize form
 */
const { handleSubmit, resetForm, setValues } = useVeeForm({
    initialValues: {
        cust_id: customer.value?.cust_id,
        shared: false,
        name: "",
        title: "",
        email: "",
        note: "",
        phones: [],
    },
    validationSchema: object<customerContact>({
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
    const formData = useForm(form);
    loading.value = true;
    toggleContLoad();

    // Determine if this is a new contact, or updating an existing one
    if (props.contactData !== undefined) {
        formData.put(
            route("customers.contacts.update", props.contactData.cont_id),
            {
                onFinish: () => {
                    toggleContLoad();
                    loading.value = false;
                    resetForm();
                },
                onSuccess: () => emit("success"),
            }
        );
    } else {
        formData.post(route("customers.contacts.store"), {
            onFinish: () => {
                toggleContLoad();
                loading.value = false;
                resetForm();
            },
            onSuccess: () => emit("success"),
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
