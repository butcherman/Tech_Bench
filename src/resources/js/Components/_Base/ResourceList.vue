<template>
    <v-list>
        <h5 v-if="!list.length" class="text-center">
            {{ emptyText ?? "Nothing to See Here..." }}
        </h5>
        <template v-for="(item, index) in list" :key="index">
            <Link :href="item[linkTag]" class="w-full h-full hover:font-bold">
                <v-list-item
                    :class="{ border: !noBorder, 'text-center': center }"
                >
                    <font-awesome-icon v-if="item.icon" :icon="item.icon" />
                    {{ item.name }}
                </v-list-item>
            </Link>
        </template>
    </v-list>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    list: any[];
    emptyText?: string;
    linkName?: string;
    noBorder?: boolean;
    center?: boolean;
}>();

const linkTag = computed(() => props.linkName ?? "href");
</script>
