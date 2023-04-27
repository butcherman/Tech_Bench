<template>
    <Modal ref="linkedCustomerModal" :title="`Customers linked to ${name}`">
        <Overlay :loading="loading">
            <div v-if="loading" class="loading-box"></div>
            <ul class="list-group">
                <li class="list-group-item" v-for="cust in custList" :key="cust.cust_id">
                    <Link :href="route('customers.show', cust.slug)" @click="linkedCustomerModal?.hide()">
                        {{ cust.name }}
                    </Link>
                </li>
            </ul>
        </Overlay>
    </Modal>
</template>

<script setup lang="ts">
    import Modal                 from '../Base/Modal/Modal.vue';
    import Overlay               from '../Base/Overlay.vue';
    import axios                 from 'axios';
    import { Link }              from '@inertiajs/vue3';
    import { ref, onMounted }    from 'vue';
    import type { customerType } from '@/Types';

    const props = defineProps<{
        name: string;
        slug: string;
    }>();

    const linkedCustomerModal = ref<InstanceType<typeof Modal> | null>(null);
    const custList            = ref<customerType[]>([]);
    const loading             = ref<boolean>(false);

    onMounted(() => {
        getCustList();
        linkedCustomerModal.value?.show();
    });

    const getCustList = () => {
        loading.value = true;
        axios.get(route('customers.linked', props.slug)).then(res => {
            custList.value = res.data;
            loading.value  = false;
        });
    }
</script>

<style scoped lang="scss">
    .loading-box {
        min-height: 100px;
    }
</style>
