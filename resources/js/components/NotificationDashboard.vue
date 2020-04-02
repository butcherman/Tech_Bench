<template>
   <div class="notification-wrapper">
       <div v-if="error">
            <h5 class="text-center">Problem Loading Notifications...</h5>
        </div>
        <div v-else-if="loading">
            <h5 class="text-center">Loading Notifications</h5>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
        <b-list-group v-else>
            <b-list-group-item v-if="notifications.length == 0" class="text-center">No Notifications</b-list-group-item>
            <b-list-group-item v-for="note in notifications" :key="note.id" :variant="!note.read_at ? 'dark' : 'light'" class="notification-message text-nowrap overflow-hidden unread" :href="note.data.link">
                <div class="row justify-content-between">
                    <div class="col-1 order-1 order-sm-1">
                        <i class="far fa-flag"></i>
                    </div>
                    <div class="col-sm-9 order-3 order-sm-2">
                        {{note.data.message}}
                    </div>
                    <div class="col-4 col-sm-2 order-2 order-sm-3 text-right">
                        <b-spinner v-show="note.data.type === 'loading'" variant="primary" type="grow" small></b-spinner>
                        <i v-if="!note.read_at" class="fas fa-check pointer" @click="markRead($event, note)" title="Mark As Read" v-b-tooltip:hover></i>
                        <i class="fas fa-trash-alt pointer" @click="delNotification($event, note)" title="Delete Notification" v-b-tooltip:hover></i>
                    </div>
                </div>
            </b-list-group-item>
        </b-list-group>
   </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: true,
                error: false,
                notifications: [],
                unreadClass: 'unread'
            }
        },
        methods: {
            getNotifications()
            {
                axios.get(this.route('getNotifications'))
                    .then(res => {
                        this.notifications = res.data;
                        this.loading = false;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            markRead(e, note)
            {
                e.preventDefault();
                note.data.type = 'loading';
                axios.get(this.route('markNotification', note.id))
                    .then(res => {
                        this.getNotifications();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            delNotification(e, note)
            {
                e.preventDefault();
                note.data.type = 'loading';
                axios.delete(this.route('delNotification', note.id))
                    .then(res => {
                        this.getNotifications();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        created()
        {
            this.getNotifications();
        }
    }
</script>
