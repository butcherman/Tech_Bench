<template>
   <div>
       <div v-for="note in notifications">
           <b-alert dismissible show :variant="note.data.type" class="text-center" @dismissed="dismissAlert(note.id)">
               <a :href="note.data.link" class="alert-link" v-if="note.data.link != null" @click="followLink(note.id, note.data.link)">{{note.data.message}}</a>
               <span v-else>{{note.data.message}}</span>
           </b-alert>
       </div>
   </div>
   
    
</template>

<script>
    export default {
        props: [
            'notification_route',
            'dismiss_route',
        ],
        data() {
            return {
                note: '',
                notifications: [],
            }
        },
        methods: {
            getNotifications()
            {
                axios.get(this.notification_route)
                    .then(res => {
                        console.log(res.data);
                        this.notifications = res.data
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            dismissAlert(id)
            {
                axios.get(this.dismiss_route.replace(':id', id))
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            followLink(id, link)
            {
                axios.get(this.dismiss_route.replace(':id', id))
                    .then(res => {
                        window.location.href = link;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            }
        },
        created() 
        {
            this.getNotifications();
        }
    }
</script>
