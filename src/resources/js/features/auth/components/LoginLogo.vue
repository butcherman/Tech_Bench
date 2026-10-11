<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import { computed } from "vue";
import { useAppData } from "@/core/state/appData";

const props = defineProps<{
    homeLinks: LinkList[];
    publicLink: LinkList | false;
    welcomeMessage?: string;
}>();

const { appName, logo } = useAppData();

const hasAdditionalData = computed(
    () => props.homeLinks.length || props.publicLink || props.welcomeMessage,
);
</script>

<template>
    <div class="w-full">
        <h1 class="text-center text-white">{{ appName }}</h1>
        <Card>
            <div><img :src="logo" class="justify-self-center" /></div>
            <hr v-if="hasAdditionalData" class="bg-gray-500 my-2" />
            <h6 class="text-center font-semibold">
                {{ welcomeMessage }}
            </h6>
            <ul v-if="homeLinks.length || publicLink" class="text-center">
                <li v-if="publicLink">
                    <Link :href="publicLink.url">
                        {{ publicLink.text }}
                    </Link>
                </li>
                <li
                    v-for="link in homeLinks"
                    :key="link.url"
                    class="list-group-item"
                >
                    <a :href="link.url" target="_blank">
                        {{ link.text }}
                    </a>
                </li>
            </ul>
        </Card>
    </div>
</template>
