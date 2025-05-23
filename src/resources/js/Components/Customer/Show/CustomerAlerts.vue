<script setup lang="ts">
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { alerts } from "@/Composables/Customer/CustomerData.module";
import { getStatusIcon, getStatusType } from "@/Composables/styleData.module";
</script>

<template>
    <div>
        <ResourceList v-if="alerts.length" :list="alerts" compact>
            <template #list-item="{ item }">
                <div
                    class="flex grow rounded-lg"
                    :class="getStatusType(item.type)"
                >
                    <div class="px-3 py-2">
                        <fa-icon :icon="getStatusIcon(item.type)" />
                    </div>
                    <div class="grow text-center py-2">
                        {{ item.message }}
                    </div>
                    <div class="px-3 py-2">
                        <fa-icon :icon="getStatusIcon(item.type)" />
                    </div>
                </div>
            </template>
        </ResourceList>
    </div>
</template>
