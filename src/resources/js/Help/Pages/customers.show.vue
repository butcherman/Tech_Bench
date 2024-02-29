<template>
    <div>
        <h5 class="text-center">Show Customer</h5>
        <p class="text-center">
            Customer information is shown here. If the customer has multiple
            sites, information for all sites will be shown here. Select a
            specific site to drill down to that sites information.
        </p>
        <!-- TODO - Add Additional Information -->
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
