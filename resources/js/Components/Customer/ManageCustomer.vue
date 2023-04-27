<template>
    <button
        class="btn btn-danger btn-pill w-75 my-1"
        title="Manage Customer"
        v-tooltip
        @click="manageCustomerModal?.show"
    >
        <fa-icon icon="fa-tasks" />
        Manage
    </button>
    <Modal
        ref="manageCustomerModal"
        title="Manage Customer"
        @show="getDeletedItems"
    >
        <div class="text-center mt-2">
            <Overlay :loading="loading">
                <template v-for="(group, index) in deletedList" :key="index">
                    <template v-if="group.length">
                        <h5>Deleted {{ index }}</h5>
                        <ul class="list-group">
                            <li
                                v-for="item in group"
                                :key="item.item_id"
                                class="list-group-item"
                            >
                                <button
                                    class="btn btn-small text-danger float-start"
                                    title="Permanently Delete"
                                    v-tooltip
                                    @click="forceDelete(index, item)"
                                >
                                    <fa-icon icon="fa-trash-can" />
                                </button>
                                <button
                                    class="btn btn-small text-warning float-start"
                                    title="Restore Item"
                                    v-tooltip
                                    @click="restoreItem(index, item)"
                                >
                                    <fa-icon icon="fa-trash-arrow-up" />
                                </button>
                                {{ item.item_name }}
                                <span class="float-end">
                                    Deleted: {{ item.item_deleted }}
                                </span>
                            </li>
                        </ul>
                    </template>
                </template>
                <button
                    v-if="canLink"
                    class="btn btn-info w-75 my-1"
                    @click="openCustSearch"
                >
                    Link Customer
                </button>
                <button
                    v-if="isLinked"
                    class="btn btn-info w-75 my-1"
                    @click="unlinkCust"
                >
                    Unlink Customer
                </button>
                <button
                    v-if="canLink"
                    class="btn btn-danger w-75 my-1"
                    @click="deactivateCust"
                >
                    Deactivate Customer
                </button>
            </Overlay>
        </div>
    </Modal>
</template>

<script setup lang="ts">
    import Modal                     from '@/Components/Base/Modal/Modal.vue';
    import Overlay                   from '@/Components/Base/Overlay.vue';
    import axios                     from 'axios';
    import { ref, inject, computed } from 'vue';
    import { router }               from '@inertiajs/vue3';
    import { useForm }               from '@inertiajs/vue3';
    import { verifyModal }           from '@/Modules/verifyModal.module';
    import { customerSearchBox }     from '@/Modules/customerSearchBox.module';
    import type { InertiaForm }      from '@inertiajs/vue3';
    import type { customerType }     from '@/Types';
    import type { Ref }              from 'vue';

    interface linkFormType {
        cust_id  : number | undefined;
        parent_id: number | null;
        add      : boolean;
    }

    interface deletedItemType {
        item_id     : number;
        item_name   : string;
        item_deleted: string;
    }

    interface deletedListType {
        equipment: deletedItemType[];
    }

    const manageCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
    const customer            = inject<Ref<customerType>>('customer');
    const loading             = ref<boolean>(false);
    const deletedList         = ref<deletedListType>();

    const canLink  = computed(() => {
        return !(customer?.value.parent_id ||
                    (customer?.value.child_count && customer?.value.child_count > 0)
                );
    })
    const isLinked = computed(() => {
        return customer?.value.parent_id !== null;
    });

    /**
     * Fetch the deleted items for this customer
     */
    const getDeletedItems = async () => {
        loading.value = true;

        await axios.get(route('customers.deleted-items', customer?.value.slug)).then(res => {
            deletedList.value = res.data;
        });

        loading.value = false;
    }

    /**
     * Restore an item
     */
    const restoreItem = async (group:string, item:deletedItemType) => {
        manageCustomerModal.value?.hide();

        router.get(route(`customers.${group}.restore`, item.item_id), {
            only     : ['equipment', 'flash']
        });
    }

    /**
     * Force Delete an item
     */
    const forceDelete = (group:string, item:deletedItemType) => {
        console.log('force delete', item);

        verifyModal('All information for this Equipment will be lost forever.  This cannot be undone').then(res => {
            if(res)
            {
                manageCustomerModal.value?.hide();
                Inertia.delete(route(`customers.${group}.force-delete`, item.item_id), {
                    only: ['flash', 'equipment'],
                });
            }
        });
    }

    /**
     * Link this customer to a parent site
     */
    const openCustSearch = () => {
        customerSearchBox().then((res) => {
            const formData = useForm({
                cust_id  : customer?.value.cust_id,
                parent_id: (res as customerType).cust_id,
                add      : true,
            });

            processLink(formData);
        });
    }

    /**
     * Remove an existing link to a parent site
     */
    const unlinkCust = () => {
        verifyModal('All shared data will no longer be attached to this customer').then(res => {
            if(res)
            {
                const formData = useForm({
                    cust_id  : customer?.value.cust_id,
                    parent_id: null,
                    add      : false,
                });

                processLink(formData);
            }
        });
    }

    /**
     * Process the add/remove link
     */
    const processLink = (formData:InertiaForm<linkFormType>) => {
        loading.value = true;
        formData.post(route('customers.set-link'), {
            only    : ['customer', 'flash'],
            onFinish: () => {
                manageCustomerModal.value?.hide();
                loading.value = false;
            },
        });
    }

    /**
     * Soft Delete a customer so they can no longer be accessed
     */
    const deactivateCust = () => {
        verifyModal('No one will be able to access this customer or their information').then(res => {
            if(res)
            {
                manageCustomerModal.value?.hide();
                Inertia.delete(route('customers.destroy', customer?.value.slug));
            }
        });
    }
</script>
