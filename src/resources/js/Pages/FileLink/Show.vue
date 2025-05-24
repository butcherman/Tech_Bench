<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import LinkActions from "@/Components/FileLink/LinkActions.vue";
import LinkDetails from "@/Components/FileLink/LinkDetails.vue";
import { ref, reactive, onMounted } from "vue";
import { Message } from "primevue";

const props = defineProps<{
    link: fileLink;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <Message
            v-if="link.is_expired"
            class="my-3"
            severity="error"
            size="large"
            pt:content:class="w-full text-center block"
        >
            <div class="flex px-4">
                <fa-icon class="pt-1" icon="triangle-exclamation" />
                <span class="grow"> Link Has Expired </span>
                <fa-icon class="pt-1" icon="triangle-exclamation" />
            </div>
        </Message>
        <div class="flex flex-col md:flex-row gap-3">
            <Card class="md:basis-2/3" title="Details">
                <LinkDetails :link="link" />
            </Card>
            <Card class="grow">
                <LinkActions :link="link" />
            </Card>
        </div>
    </div>
</template>
