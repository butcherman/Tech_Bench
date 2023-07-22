<template>
    <div>
        <Head title="Dashboard" />
        <div class="row">
            <div class="col-12">
                <h4 class="text-center text-md-left">Dashboard</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button
                            class="btn btn-dark w-75 m-2"
                            @click="pushAlert('success', 'this is an alert')"
                        >
                            New Flash Alert
                        </button>
                        <button class="btn btn-info w-75 m-2">New Broadcasting Alert</button>
                        <button class="btn btn-info w-75 m-2" @click="sendPrivateEvent">New Private Event</button>
                        <button class="btn btn-info w-75 m-2" @click="sendPublicEvent">New Public Event</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
// import { ref, reactive, onMounted } from 'vue';
import { pushAlert } from "@/State/LayoutState";
import axios from "axios";

// const props = defineProps<{}>();


const sendPrivateEvent = () => {
    axios.get(route('private-event')).then(res => console.log(res));
}

const sendPublicEvent = () => {
    axios.get(route('public-event')).then(res => console.log(res));
}

console.log(Echo);

Echo.channel('public').listenToAll((event, data) => {
    console.log(event, data);
});

Echo.private('user.1').listenToAll((event, data) => {
    console.log(event, data);
});

</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
