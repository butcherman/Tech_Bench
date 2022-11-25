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
                <!-- <button class="btn btn-info w-75 my-1">Unlink Customer</button> -->
                <!-- <button class="btn btn-info w-75 my-1">Deactivate Customer</button> -->
            </Overlay>
        </div>
    </Modal>
</template>

<script setup lang="ts">
    import Modal from '@/Components/Base/Modal/Modal.vue';
    import Overlay from '@/Components/Base/Overlay.vue';
    import { ref, inject, computed, onMounted } from 'vue';
    import { customerSearchBox }     from '@/Modules/customerSearchBox.module';
    import { useForm } from '@inertiajs/inertia-vue3';
    import type { customerType } from '@/Types';
    import type { Ref } from 'vue';

    const manageCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
    const customer            = inject<Ref<customerType>>('customer');
    const loading             = ref<boolean>(false);

    const canLink = computed(() => {
        return !(customer?.value.parent_id || (customer?.value.child_count && customer?.value.child_count > 0));
    })

    /**
     * Link this customer to a parent site
     */
    const openCustSearch = () => {
        customerSearchBox().then((res) => {
            loading.value = true;
            const formData = useForm({
                cust_id  : customer?.value.cust_id,
                parent_id: res.cust_id,
                add      : true,
            });

            formData.post(route('customers.set-link'), {
                only: ['customer', 'flash'],
                onFinish: () => {
                    manageCustomerModal.value?.hide();
                    loading.value = false;
                },
            });
        });
    }
</script>
