<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";

// TODO - Add Page.
const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<template>
    <Card class="tb-card">
        <h4 class="text-center">Coming Soon</h4>
    </Card>
</template>
