<template>
    <div id="home-wrapper">
        <div id="content-wrapper">
            <h1 class="text-center text-light">{{ app.name }}</h1>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <img :src="app.logo" class="img-fluid w-50" />
                                <hr v-if="welcomeMessage || homeLinks.length" />
                                <h6 v-if="welcomeMessage" class="text-center">
                                    {{ welcomeMessage }}
                                </h6>
                                <ul v-if="homeLinks.length" class="list-group">
                                    <li
                                        v-for="link in homeLinks"
                                        :key="link.url"
                                        class="list-group-item"
                                    >
                                        <a :href="link.url">{{ link.text }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="login-form-wrapper">
            <div id="login-form">
                <h5 class="text-center">Tech Login:</h5>
                <LoginForm />
                <div class="separator">or</div>
                <button v-if="allowOath" class="btn btn-primary w-100 my-1">
                    Login With Office 365
                </button>
                <button class="btn btn-primary w-100 my-1">
                    Forgot Password
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import LoginForm from "@/Forms/Auth/LoginForm.vue";
import { useAppStore } from "@/Store/AppStore";

interface homeLinks {
    text: string;
    url: string;
}

defineProps<{
    welcomeMessage?: string;
    homeLinks: homeLinks[];
    allowOath: boolean;
}>();

const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<style lang="scss">
@import "../../../scss/_global_variables.scss";

#home-wrapper {
    height: 100vh;
    width: 100vw;

    /**
    * Mobile/Small Screens
    */
    @media (max-width: $brk-lg) {
        display: block;
        padding-top: 50px;

        #content-wrapper {
            width: 95%;
            margin: auto;
        }
        #login-form-wrapper {
            width: 95%;
            margin: auto;
            margin-top: 1rem;
            border-radius: 5px;
        }
    }
    /**
    * Standard screens
    */
    @media (min-width: $brk-lg) {
        display: flex;
        flex-direction: row;
        align-items: center;

        #content-wrapper {
            width: 65%;
            display: flex;
            flex-direction: column;
            align-self: center;
        }
        #login-form-wrapper {
            width: 35%;
            height: 100%;

            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }
    }

    #login-form-wrapper {
        background-color: #ffffff;
        display: flex;
        flex-direction: row;
        align-items: center;
        #login-form {
            width: 100%;
            margin-left: 4px;
            margin-right: 4px;
            padding: 5px;
        }
    }
}
</style>
