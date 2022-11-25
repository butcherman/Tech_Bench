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
    <Modal ref="manageCustomerModal" title="Manage Customer">
        Deleted Stuff...
        <div class="text-center mt-2">
            <Overlay :loading="loading">
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
    import { ref, inject, computed } from 'vue';
    import { Inertia }               from '@inertiajs/inertia';
    import { InertiaForm, useForm }  from '@inertiajs/inertia-vue3';
    import { verifyModal }           from '@/Modules/verifyModal.module';
    import { customerSearchBox }     from '@/Modules/customerSearchBox.module';
    import type { customerType }     from '@/Types';
    import type { Ref }              from 'vue';

    interface linkFormType {
        cust_id  : number | undefined;
        parent_id: number | null;
        add      : boolean;
    }

    const manageCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
    const customer            = inject<Ref<customerType>>('customer');
    const loading             = ref<boolean>(false);

    const canLink  = computed(() => {
        return !(customer?.value.parent_id || (customer?.value.child_count && customer?.value.child_count > 0));
    })
    const isLinked = computed(() => {
        return customer?.value.parent_id !== null;
    });

    /**
     * Link this customer to a parent site
     */
    const openCustSearch = () => {
        customerSearchBox().then((res) => {
            const formData = useForm({
                cust_id  : customer?.value.cust_id,
                parent_id: res.cust_id,
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
