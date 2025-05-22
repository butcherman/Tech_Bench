<script setup lang="ts">
import KbLayout from "@/Layouts/KnowledgeBase/KbLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";

// TODO - Add Page.
const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <h4 class="text-center">Coming Soon</h4>
        </Card>
    </div>
</template>
