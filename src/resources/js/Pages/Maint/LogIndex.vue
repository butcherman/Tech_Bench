<template>
    <div>
        <Card class="tb-card">
            <div class="flex flex-row justify-center">
                <BaseButton
                    v-for="channel in channels"
                    class="basis-64 mx-2"
                    :href="$route('maint.logs.index', channel)"
                    :text="channel.toUpperCase()"
                />
            </div>
        </Card>
        <Card class="tb-card">
            <div v-if="!channel">
                <h5 class="text-center">
                    Select a Log Channel to View Log Files
                </h5>
            </div>
            <div v-else>
                <ResourceList
                    :list="logList ?? []"
                    :link-fn="
                        (item) => $route('maint.logs.show', [channel, item])
                    "
                >
                    <template #actions="{ item }">
                        <a
                            :href="
                                $route('maint.logs.download', [channel, item])
                            "
                        >
                            <BaseBadge class="mx-1" icon="download" />
                        </a>
                    </template>
                </ResourceList>
            </div>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

defineProps<{
    channels: string[];
    channel?: string;
    logList?: string[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
