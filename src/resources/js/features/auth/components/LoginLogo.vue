<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogoImage from "@/core/components/LogoImage.vue";
import { useAppData } from "@/core/state/appData";

const props = defineProps<{
    welcomeMessage?: string;
    homeLinks: linkList[];
    publicLink: linkList | false;
}>();

const { appName } = useAppData();
</script>

<template>
    <div class="w-full">
        <h1 class="text-center text-white">{{ appName }}</h1>
        <Card size="large">
            <LogoImage />
            <hr class="bg-gray-500 my-2" />
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
