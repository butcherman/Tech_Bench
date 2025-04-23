<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";

interface deletedItems {
    // equipment: customerEquipment[];
    // contacts: customerContact[];
    // notes: customerNote[];
    // files: customerFile[];
}

defineProps<{
    deletedItems: deletedItems;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <h4 class="text-center">Coming Soon</h4>
            <div>
                {{ deletedItems }}
            </div>
        </Card>
    </div>
</template>
