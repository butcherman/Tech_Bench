<template>
    <div>
        <Head title="Dashboard" />
        <div class="row justify-content-center">
            <div class="col-md-10">
                <DashboardNotifications />
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button
                            class="btn btn-dark w-75 m-2"
                            @click="
                                pushAlert(
                                    'success',
                                    'This is a long Flash Alert that should wrap around and show more than one line'
                                )
                            "
                        >
                            New Flash Alert
                        </button>
                        <button
                            class="btn btn-info w-75 m-2"
                            @click="sendUserNotification"
                        >
                            New Notification Alert
                        </button>
                        <button
                            class="btn btn-info w-75 m-2"
                            @click="sendPrivateEvent"
                        >
                            New Private Event
                        </button>
                        <button
                            class="btn btn-info w-75 m-2"
                            @click="sendPublicEvent"
                        >
                            New Public Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import DashboardNotifications from "@/Components/Home/DashboardNotifications.vue";
import { pushAlert, echo } from "@/State/LayoutState";
import { useForm } from "@inertiajs/vue3";
import { v4 } from "uuid";
import axios from "axios";

// const props = defineProps<{}>();

const sendPrivateEvent = () => {
    axios.get(route("private-event")).then((res) => console.log(res));
};

const sendPublicEvent = () => {
    axios.get(route("public-event")).then((res) => console.log(res));
};

echo.channel("public").listenToAll((event, data) => {
    console.log(event, data);
});

echo.private("user.1").listenToAll((event, data) => {
    console.log(event, data);
});

const sendUserNotification = () => {
    const form = useForm({
        message: "this is a test -" + v4(),
        subject: "test - " + v4(),
    });

    form.post(route("admin.users.send-notification", "admin"), {
        onFinish: () => console.log("notification sent"),
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
