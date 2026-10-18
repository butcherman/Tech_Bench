<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import { ref, reactive, onMounted } from "vue";
import AddButton from "@/core/components/buttons/AddButton.vue";
import AlertButton from "@/core/components/buttons/AlertButton.vue";
import EditButton from "@/core/components/buttons/EditButton.vue";
import DeleteButton from "@/core/components/buttons/DeleteButton.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{}>();
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex justify-center gap-2">
            <Card title="Button Components">
                <div class="flex flex-col gap-2">
                    <BaseButton text="Base Button" />
                    <AddButton text="Add Button" />
                    <AlertButton text="Alert Button" />
                    <EditButton text="Edit Button" />
                    <DeleteButton text="Delete" />
                    <div class="mx-auto">
                        <BaseButton text="..." />
                    </div>
                </div>
            </Card>
            <Card title="Button Variants" size="md">
                <div class="flex flex-wrap gap-2">
                    <BaseButton class="flex-1" text="Danger" variant="danger" />
                    <BaseButton class="flex-1" text="Dark" variant="dark" />
                    <BaseButton class="flex-1" text="Error" variant="error" />
                    <BaseButton class="flex-1" text="Help" variant="help" />
                    <BaseButton class="flex-1" text="Info" variant="info" />
                    <BaseButton class="flex-1" text="Light" variant="light" />
                    <BaseButton
                        class="flex-1"
                        text="Primary"
                        variant="primary"
                    />
                    <BaseButton
                        class="flex-1"
                        text="Secondary"
                        variant="secondary"
                    />
                    <BaseButton
                        class="flex-1"
                        text="Success"
                        variant="success"
                    />
                    <BaseButton
                        class="flex-1"
                        text="Warning"
                        variant="warning"
                    />
                    <BaseButton class="flex-1" text="None" />
                    <BaseButton class="flex-1" text="Flat" flat />
                </div>
            </Card>
            <Card title="Button Sizes" size="md">
                <div class="flex flex-col gap-2">
                    <BaseButton variant="primary" text="Large" size="lg" />
                    <BaseButton variant="primary" text="Medium" size="md" />
                    <BaseButton variant="primary" text="Small" size="sm" />
                    <BaseButton
                        variant="primary"
                        text="Large Pill"
                        size="lg"
                        pill
                    />
                    <BaseButton
                        variant="primary"
                        text="Medium Pill"
                        size="md"
                        pill
                    />
                    <BaseButton
                        variant="primary"
                        text="Small Pill"
                        size="sm"
                        pill
                    />
                </div>
            </Card>
        </div>
    </div>
</template>
