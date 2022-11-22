<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <CustomerDetails />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import CustomerDetails from '@/Components/Customer/CustomerDetails.vue';
    import axios from 'axios';
    import { ref, reactive, onMounted, provide, InjectionKey } from 'vue';
    import { customerType } from '@/Types';

    const props = defineProps<{
        isFav   : boolean;
        customer: customerType;
    }>();

    /**
     * Bookmark Data
     */
    const isBookmark      = ref<boolean>(props.isFav);
    const bookmarkLoading = ref<boolean>(false);
    const toggleBookmark  = () => {
        bookmarkLoading.value = true;
        axios.post(route('customers.bookmark'), {
            cust_id: props.customer.cust_id,
            state: !isBookmark.value
        }).then(res => {
            bookmarkLoading.value = false;
            isBookmark.value = !isBookmark.value;
        });
    }
    provide('bookmark', { isBookmark, bookmarkLoading, toggleBookmark });

    /**
     * Customer Detail Data
     */
    provide('customer', props.customer);




</script>

<script lang="ts">
    export default { layout: App }
</script>
