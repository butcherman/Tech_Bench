<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import BannerAlert from "@/core/components/alerts/BannerAlert.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{}>();
</script>

<template>
    <div class="flex flex-col gap-2">
        <div>
            <Card
                title="Badge Components"
                sub-title="A List of Banner Alerts"
                title-icon="fa-regular fa-house"
            >
                <div class="flex flex-col w-full gap-2">
                    <BannerAlert
                        text="This is a Primary alert"
                        variant="primary"
                    />
                    <BannerAlert
                        text="This is a Danger alert"
                        variant="danger"
                    />
                    <BannerAlert
                        text="This is an Error alert"
                        variant="error"
                    />
                    <BannerAlert text="This is a Help alert" variant="help" />
                    <BannerAlert text="This is an Info alert" variant="info" />
                    <BannerAlert
                        text="This is a Success alert"
                        variant="success"
                    />
                    <BannerAlert
                        text="This is a Warning alert"
                        variant="warning"
                    />
                </div>
            </Card>
        </div>
    </div>
</template>
