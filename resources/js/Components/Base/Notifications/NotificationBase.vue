<template>
    <b-modal :title="notification.subject" ref="message-modal" @ok="markMessage">
        <component
            v-if="notification.type"
            :is="notification.type.split(/\W+/).pop()"
            :data="notification.data.data"
            @read="markMessage"
        ></component>
        <template #modal-footer="{ ok }">
            <b-button variant="info" @click="ok">OK</b-button>
            <b-button variant="danger" @click="deleteMessage">Delete</b-button>
        </template>
    </b-modal>
</template>

<script>
    export default {
        props: {
            notification: {
                type:     Object,
                required: true,
            },
        },
        data() {
            return {
                checked: false,
                loading: false,
                deleting: false,
            }
        },
        created() {
            //
        },
        mounted() {
            //
        },
        computed: {
            //
        },
        watch: {
            //
        },
        methods: {
            openModal()
            {
                console.log('show modal');
                this.$refs['message-modal'].show();
            },
            markMessage()
            {
                this.$emit('read', this.notification.id);
                this.$refs['message-modal'].hide();
            },
            deleteMessage()
            {
                this.$emit('delete', this.notification.id);
                this.$refs['message-modal'].hide();
            },
        },
    }
</script>
