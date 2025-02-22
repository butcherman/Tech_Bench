<script setup lang="ts">
import { computed } from "vue";
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import LogoImage from "@/Components/LogoImage.vue";
import TechLoginForm from "@/Forms/Auth/TechLoginForm.vue";

interface homeLinks {
    text: string;
    url: string;
}

const props = defineProps<{
    welcomeMessage?: string;
    homeLinks: homeLinks[];
    publicLink: homeLinks | false;
    allowOath: boolean;
}>();

/**
 * If there are any links or message that should go under the logo
 */
const hasLinks = computed(
    () => props.welcomeMessage || props.homeLinks.length || props.publicLink
);
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex flex-col md:flex-row h-screen">
        <div class="md:grow flex items-center justify-center">
            <div class="md:w-80 m-3">
                <LogoImage>
                    <div v-if="hasLinks" class="mt-4">
                        <hr class="bg-gray-500" />
                        <h6
                            v-if="welcomeMessage"
                            class="text-center font-semibold"
                        >
                            {{ welcomeMessage }}
                        </h6>
                        <ul
                            v-if="homeLinks.length || publicLink"
                            class="text-center"
                        >
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
                    </div>
                </LogoImage>
            </div>
        </div>
        <div
            class="flex items-center md:basis-80 bg-white m-3 md:m-0 rounded-lg md:rounded-none"
        >
            <div class="m-3 w-full">
                <h3 class="text-center">Tech Login</h3>
                <TechLoginForm />
                <div v-if="allowOath" class="w-full text-center">
                    <div class="separator">or</div>
                    <a :href="$route('azure-login')">
                        <BaseButton
                            class="md:w-3/4"
                            text="Login with Office 365"
                        />
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
