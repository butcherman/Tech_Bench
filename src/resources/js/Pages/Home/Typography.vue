<template>
    <div>
        <div class="text-center">
            <BaseButton
                text="Add Flash Message"
                class="m-2"
                @click="pushFlash"
            />
            <BaseButton
                text="Add Message Toast"
                class="m-2"
                @click="pushToast"
            />
            <BaseButton
                text="Example Modal"
                class="m-2"
                @click="dashboardModal?.show"
            />
            <BaseButton
                text="OK Prompt"
                class="m-2"
                @click="okModal('Example OK Modal', true)"
            />
        </div>
        <Modal ref="dashboardModal" title="Example Modal">
            <LogoImage dark-header> </LogoImage>
            <template #footer>
                <div class="mt-2">
                    <BaseButton
                        text="Clicky Yes"
                        variant="success"
                        class="mx-1"
                        @click="dashboardModal?.hide"
                    />
                    <BaseButton
                        text="Clicky No"
                        variant="danger"
                        class="mx-1"
                        @click="dashboardModal?.hide"
                    />
                </div>
            </template>
        </Modal>
        <div class="grid md:grid-cols-3 gap-2">
            <Card title="Typography">
                <h1>H1 Header</h1>
                <h2>H2 Header</h2>
                <h3>H3 Header</h3>
                <h4>H4 Header</h4>
                <h5>H5 Header</h5>
                <h6>H6 Header</h6>
                <p>Paragraph</p>
                <code>Code Block</code>
                <pre>Pre Block</pre>
            </Card>
            <Card title="Buttons">
                <BaseButton class="m-2" text="Button" />
                <BaseButton class="m-2" text="Pill Button" pill />
                <br />
                <AddButton class="m-2" />
                <AddButton class="m-2" text="Add Pill" pill />
                <AddBadge class="m-2" v-tooltip="'Add Badge'" />
                <br />
                <EditButton class="m-2" />
                <EditButton class="m-2" text="Edit Pill" pill />
                <EditBadge class="m-2" v-tooltip="'Edit Badge'" />
                <br />
                <DeleteButton
                    class="m-1"
                    confirm-msg="This Cannot Be Undone"
                    confirm
                    @accepted="console.log('accepted')"
                />
                <DeleteButton class="m-1" text="Delete Pill" pill />
                <DeleteBadge class="m-1" v-tooltip="'Delete Badge'" />
                <br />
                <BaseBadge
                    icon="house"
                    v-tooltip="'Base Badge'"
                    :href="$route('about')"
                    class="m-2"
                />
                <ClipboardCopy value="Random Text for Clipboard" class="m-2" />
                <RefreshButton class="m-2" />
                <h3 class="inline">
                    <BookmarkItem :is-bookmark="false" toggle-route="#" />
                </h3>
            </Card>
            <Card title="Resource List">
                <ResourceList :list="resourceListData" />
            </Card>
            <Card title="Stacked Table">
                <TableStacked
                    :rows="stackedTable"
                    title-case
                    bordered
                    class="w-full"
                />
            </Card>
            <Card title="Data Table" class="md:col-span-2">data table</Card>
            <Card title="Step Navigation" class="md:col-span-3">
                Step Navigation
            </Card>
        </div>
        <Card class="tb-card my-2" title="Form Inputs">
            <VueForm
                :initial-values="{
                    text_field_array: ['', ''],
                    phone_input_array: [
                        { type: 'mobile', number: '', ext: '' },
                    ],
                }"
                :validation-schema="{}"
                submit-route="#"
                submit-method="post"
                submit-text="Sample Form"
            >
                <TextInput
                    id="text-input"
                    label="Text Input"
                    name="text_input"
                    placeholder="This is a Text Input"
                    help="This is Help Text"
                />
                <fieldset class="border p-2">
                    <legend>Text Input Array</legend>
                    <TextInputArray
                        id="text-input-array"
                        name="text_field_array"
                        draggable
                    />
                </fieldset>
                <PasswordInput
                    id="password-input"
                    label="Password Input"
                    name="password_input"
                />
                <PhoneInput
                    id="phone-input"
                    label="Phone Number Input"
                    name="phone_input"
                />
                <fieldset class="border p-2">
                    <legend>Phone Input Array</legend>
                    <PhoneInputArray
                        id="phone-input-array"
                        name="phone_input_array"
                        :phone-types="[
                            {
                                text: 'Mobile',
                                value: 'mobile',
                            },
                            {
                                text: 'Work',
                                value: 'work',
                            },
                            {
                                text: 'Home',
                                value: 'home',
                            },
                        ]"
                    />
                </fieldset>
                <SelectInput
                    id="select-input"
                    label="Select Input"
                    name="select_input"
                    :list="normalSelect"
                />
                <SelectInput
                    id="select-input-group"
                    label="Grouped Select Input"
                    name="grouped_select_input"
                    text-field="label"
                    :list="selectGroup"
                    group-text-field="label"
                    group-children-field="items"
                />
                <MultiSelectInput
                    id="multiselect-input"
                    label="Multi Select Input"
                    name="multi_select"
                    :list="normalSelect"
                />
                <TextAreaInput
                    id="text-area-input"
                    label="Text Area Input"
                    name="text_area_input"
                />
                <OtpInput
                    id="otp-input"
                    label="OTP Input"
                    name="otp_input"
                    :length="6"
                />
                <SwitchInput
                    id="switch-input"
                    label="Switch Input"
                    name="switch_input"
                    center
                />
            </VueForm>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AddBadge from "@/Components/_Base/Badges/AddBadge.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import Card from "@/Components/_Base/Card.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
import Modal from "@/Components/_Base/Modal.vue";
import okModal from "@/Modules/okModal";
import OtpInput from "@/Forms/_Base/OtpInput.vue";
import PasswordInput from "@/Forms/_Base/PasswordInput.vue";
import PhoneInput from "@/Forms/_Base/PhoneInput.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { ref } from "vue";
import { useAppStore } from "@/Stores/AppStore";
import { useBroadcastStore } from "@/Stores/BroadcastStore";
import MultiSelectInput from "@/Forms/_Base/MultiSelectInput.vue";
import PhoneInputArray from "@/Forms/_Base/PhoneInputArray.vue";
import TextInputArray from "@/Forms/_Base/TextInputArray.vue";

const app = useAppStore();
const broad = useBroadcastStore();

const dashboardModal = ref<InstanceType<typeof Modal> | null>(null);

const index = ref(0);
const pushFlash = () => {
    console.log("message pushed");
    app.pushFlashMsg({
        type: "warning",
        message: `Message - ${index.value}`,
    });
    index.value++;
};

const pushToast = () => {
    console.log("pushing toast");
    broad.pushToastMsg(`Message - ${index.value}`, "A Title", route("about"));
    index.value++;
};

const resourceListData = ref([
    {
        icon: "pencil",
        text: "This is A Link",
        href: route("about"),
    },
    {
        icon: "eye",
        text: "This is a regular item",
    },
    {
        text: "So is this",
    },
    {
        text: "And this",
    },
]);

const stackedTable = ref({
    row_1: "Data 1",
    row_2: "Data 2",
    row_3: "Data 3",
    row_4: "Data 4",
    true_value: true,
    false_value: false,
});

const normalSelect = ref([
    { text: "New York", value: "NY" },
    { text: "Rome", value: "RM" },
    { text: "London", value: "LDN" },
    { text: "Istanbul", value: "IST" },
    { text: "Paris", value: "PRS" },
]);

const selectGroup = ref([
    {
        label: "Germany",
        items: [
            { label: "Berlin", value: "Berlin" },
            { label: "Frankfurt", value: "Frankfurt" },
            { label: "Hamburg", value: "Hamburg" },
            { label: "Munich", value: "Munich" },
        ],
    },
    {
        label: "USA",
        items: [
            { label: "Chicago", value: "Chicago" },
            { label: "Los Angeles", value: "Los Angeles" },
            { label: "New York", value: "New York" },
            { label: "San Francisco", value: "San Francisco" },
        ],
    },
    {
        label: "Japan",
        items: [
            { label: "Kyoto", value: "Kyoto" },
            { label: "Osaka", value: "Osaka" },
            { label: "Tokyo", value: "Tokyo" },
            { label: "Yokohama", value: "Yokohama" },
        ],
    },
]);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
