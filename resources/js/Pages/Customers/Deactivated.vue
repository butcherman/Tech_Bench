<template>
    <Head title="Deactivated Customers" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Deactivated Customers</div>
                        <Overlay :loading="loading">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input
                                                id="check-all"
                                                ref="checkAllMaster"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="check-all"
                                                v-model="allCheck"
                                                @change="checkAll"
                                            />
                                        </th>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>Deactivated Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td v-if="!customers.length" colspan="5" class="text-center">
                                            <h5>No Deactivated Customers</h5>
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="cust in customers"
                                        :key="cust.cust_id"
                                    >
                                        <td>
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="selected"
                                                :value="cust.cust_id"
                                                @change="intermediateCheck"
                                            />
                                        </td>
                                        <td>{{ cust.cust_id }}</td>
                                        <td>{{ cust.name }}</td>
                                        <td>{{ cust.city }}</td>
                                        <td>{{ cust.deleted_at }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button
                                                class="btn btn-danger float-end mx-2"
                                                @click="remove"
                                            >
                                                <fa-icon icon="fa-trash-can" />
                                                Delete Selected
                                            </button>
                                            <button
                                                class="btn btn-warning float-end mx-2"
                                                @click="restore"
                                            >
                                                <fa-icon
                                                    icon="fa-trash-arrow-up"
                                                />
                                                Restore Selected
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </Overlay>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import Overlay from "@/Components/Base/Overlay.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { verifyModal } from "@/Modules/verifyModal.module";

const props = defineProps<{
    customers: customer[];
}>();

const checkAllMaster = ref<InstanceType<typeof HTMLInputElement> | null>(null);
const loading = ref<boolean>(false);
const allCheck = ref<boolean>(false);
const selected = ref<number[]>([]);

/**
 * Check/Uncheck all items
 */
const checkAll = (): void => {
    if (allCheck.value) {
        props.customers.forEach((cust) => {
            selected.value.push(cust.cust_id);
        });
    } else {
        selected.value = [];
    }
};

/**
 * For styling, trigger the indeterminate pseudo class on the input box
 */
const intermediateCheck = (): void => {
    allCheck.value = false;
    if (checkAllMaster.value !== null) {
        checkAllMaster.value.indeterminate = true;
    }
};

/**
 * Remove the soft deleted date for the selected customers
 */
const restore = (): void => {
    loading.value = true;
    const formData = useForm({
        cust_list: selected.value,
    });

    formData.post(route("customers.restore"), {
        onFinish: () => (loading.value = false),
    });
};

const remove = (): void => {
    verifyModal(
        "All data will be removed for this customer.  This cannot be undone"
    ).then((res) => {
        if (res) {
            loading.value = true;
            const formData = useForm({
                cust_list: selected.value,
            });

            formData.delete(route("customers.force-delete"), {
                onFinish: () => (loading.value = false),
            });
        }
    });
};
</script>

<script lang="ts">
export default { layout: App };
</script>
