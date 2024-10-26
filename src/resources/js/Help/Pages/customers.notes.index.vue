<template>
    <div>
        <h5 class="text-center">Customer Notes</h5>
        <customerSiteNotes />
    </div>
</template>

<script setup lang="ts">
import customerSiteNotes from "../Components/customer.site.notes.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
