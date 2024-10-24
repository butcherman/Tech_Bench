<template>
    <div>
        <h5 class="text-center">Customer Equipment</h5>
        <customerSiteEquipmentList class="text-center" />
    </div>
</template>

<script setup lang="ts">
import customerSiteEquipmentList from "../Components/customer.site.equipmentList.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
