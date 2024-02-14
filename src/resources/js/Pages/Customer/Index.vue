<template>
    <div>
        <Head title="Customers" />
        <div
            v-if="permissions.details.create"
            class="row justify-content-center my-2"
        >
            <div class="col">
                <AddButton
                    class="float-end"
                    small
                    pill
                    @click="selectCustTypeModal?.show"
                >
                    Add New Customer
                </AddButton>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <CustomerSearchForm />
                        <div class="text-center">
                            <div
                                class="form-check form-switch form-check-inline"
                            >
                                <input
                                    v-model="showSiteList"
                                    id="show-site-list"
                                    class="form-check-input"
                                    type="checkbox"
                                />
                                <label
                                    for="show-site-list"
                                    class="form-check-label"
                                >
                                    Show Customer Sites (where available)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <CustomerSearchTable />
                    </div>
                </div>
            </div>
        </div>
        <Modal ref="selectCustTypeModal">
            <div class="text-center">
                <Link
                    as="button"
                    :href="$route('customers.create')"
                    class="btn btn-info w-75 m-2"
                >
                    New Customer
                </Link>
                <Link
                    as="button"
                    :href="$route('customers.site.create')"
                    class="btn btn-info w-75 m-2"
                >
                    Add Site to Existing Customer
                </Link>
            </div>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/_Base/Modal.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import CustomerSearchTable from "@/Components/Customer/CustomerSearchTable.vue";
import { ref, onMounted } from "vue";
import {
    triggerSearch,
    showSiteList,
} from "../../Modules/CustomerSearch.module";

onMounted(() => triggerSearch());

defineProps<{
    permissions: customerPermissions;
}>();

const selectCustTypeModal = ref<InstanceType<typeof Modal> | null>(null);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
