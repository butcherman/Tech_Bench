<script setup lang="ts">
import { computed } from "vue";
import { useAppData } from "@/core/state/appData";

const props = defineProps<{
    homeLinks: LinkList[];
    publicLink: LinkList | false;
    welcomeMessage?: string;
}>();

const { logo } = useAppData();

const hasAdditionalData = computed(
    () => props.homeLinks.length || props.publicLink,
);
</script>

<template>
    <div class="flex flex-col gap-3 items-center">
        <div class="w-1/2 bg-white rounded-lg p-2">
            <img :src="logo" class="justify-self-center" />
        </div>
        <div>
            <h1 class="text-center md:text-white">{{ welcomeMessage }}</h1>
        </div>
        <div
            v-if="hasAdditionalData"
            class="border-t border-t-slate-300 w-full mt-3 pt-3"
        >
            <h4 v-if="publicLink" class="text-center md:text-white">
                <Link :href="publicLink.url">
                    {{ publicLink.text }}
                </Link>
            </h4>
            <div class="flex flex-row justify-center md:text-white mt-2 gap-2">
                <div
                    v-for="link in homeLinks"
                    :key="link.url"
                    class="list-group-item border-e border-slate-300 pe-2 text-center last:border-0"
                >
                    <a :href="link.url" target="_blank">{{ link.text }}</a>
                </div>
            </div>
        </div>
    </div>
</template>
