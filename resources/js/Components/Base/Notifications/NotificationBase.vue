<template>
    <b-tr>
        <b-td></b-td>
        <b-td class="pointer" @click="openModal">
            <i v-if="notification.read_at === null" class="fas fa-envelope"></i>
            <i v-else class="fas fa-envelope-open-text"></i>
            {{notification.data.subject}}
        </b-td>
        <b-td>{{notification.created_at}}</b-td>
        <b-modal title="Message" ref="message-modal" @ok="markMessage">
            <component :is="notification.type.split(/\W+/).pop()" :data="notification.data.data" @read="markMessage"></component>
            <template #modal-footer="{ ok }">
                <b-button variant="info" @click="ok">OK</b-button>
                <b-button variant="danger" @click="deleteMessage">Delete</b-button>
            </template>
        </b-modal>
    </b-tr>
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
                //
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
                this.$refs['message-modal'].show();
            },
            markMessage()
            {
                this.$emit('read', this.notification.id);
            },
            deleteMessage()
            {
                this.$emit('delete', this.notification.id);
                this.$refs['message-modal'].hide();
            }
        },
    }
</script>
