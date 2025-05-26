<script setup lang="ts">
import AtomLoader from "../_Base/Loaders/AtomLoader.vue";
import CustomerSearchModal from "../Customer/Search/CustomerSearchModal.vue";
import Modal from "../_Base/Modal.vue";
import MoveFileForm from "@/Forms/FileLink/MoveFileForm.vue";
import { checkCustId } from "@/Composables/Customer/CheckCustomerId.module";
import { dataGet } from "@/Composables/axiosWrapper.module";
import { ref, useTemplateRef } from "vue";

const props = defineProps<{
    link: fileLink;
}>();

const searchModal = useTemplateRef("search-modal");
const formModal = useTemplateRef("move-file-modal");

const selectedCustomer = ref<customer>();
const movingFile = ref<fileLinkFile>();
const equipmentList = ref<customerEquipment[]>();
const fileTypes = ref<customerFileType[]>();

/**
 * Initialize the Move process by checking for a customer profile attached to link.
 */
const triggerMove = (file: fileLinkFile): void => {
    movingFile.value = file;

    if (props.link.cust_id) {
        checkCustId(props.link.cust_id).then((res) => {
            if (res.customer) {
                assignCustomer(res.customer);
            }
        });

        return;
    }

    searchModal.value?.show();
};

/**
 * Assign the customer profile and show the Customer File Form
 */
const assignCustomer = (cust: customer): void => {
    selectedCustomer.value = cust;
    formModal.value?.show();

    dataGet(route("customers.files.index", cust.slug)).then((res) => {
        if (res) {
            console.log(res);
            equipmentList.value = res.data.equipmentList;
            fileTypes.value = res.data.fileTypes;
        }
    });
};

/**
 * Clear the selected customer and show form to assign a different customer.
 */
const clearCustomer = (): void => {
    selectedCustomer.value = undefined;

    formModal.value?.hide();
    searchModal.value?.show();
};

defineExpose({ triggerMove });
</script>

<template>
    <div>
        <CustomerSearchModal ref="search-modal" @selected="assignCustomer" />
        <Modal ref="move-file-modal" size="large">
            <h3 class="text-center">
                Moving File to {{ selectedCustomer?.name }}
                <span
                    class="text-purple-400 pointer"
                    v-tooltip="'Change Customer'"
                    @click="clearCustomer()"
                >
                    <fa-icon icon="retweet" />
                </span>
            </h3>
            <MoveFileForm
                v-if="
                    selectedCustomer && equipmentList && fileTypes && movingFile
                "
                :link="link"
                :customer="selectedCustomer"
                :file="movingFile"
                :equipment-list="equipmentList"
                :file-types="fileTypes"
                @success="formModal?.hide()"
            />
            <div v-else class="flex justify-center">
                <AtomLoader />
            </div>
        </Modal>
    </div>
</template>
