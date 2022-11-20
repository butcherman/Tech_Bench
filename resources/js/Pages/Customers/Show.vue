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
    import { ref, reactive, onMounted, provide, InjectionKey } from 'vue';
    import { customerType } from '@/Types';


    const props = defineProps<{
        isFav   : boolean;
        customer: customerType;
    }>();

    /**
     * Bookmark Data
     */
    // const isBookmark     = ref<boolean>(props.isFav);
    // const toggleBookmark = () => {
    //     console.log('toggle');

    //     isBookmark.value = !isBookmark.value;

    //     console.log(isBookmark.value);
    // }


    /**
     * Customer Detailed Information
     */
    provide('customer', props.customer);





    // /**
    //  * List of state provides for other components
    //  */
    // provide(isFav, { isBookmark, toggleBookmark });
    // provide('customer', props.customer);
</script>

<script lang="ts">
    export default { layout: App }
</script>
