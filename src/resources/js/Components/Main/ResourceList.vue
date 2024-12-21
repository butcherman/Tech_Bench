<template>
    <v-list>
        <template v-for="(item, index) in list" :key="index">
            <Link :href="item.href" class="w-full h-full">
                <v-list-item class="text-center border">
                    {{ item.name }}
                </v-list-item>
            </Link>
        </template>
    </v-list>
</template>

<script setup lang="ts">
defineProps<{
    list: {
        name: string;
        href: string;
    }[];
}>();
</script>
