<template>
    <b-tr class="row">
        <b-td class="col-1 text-center" @click="toggleChecked">
            <i v-if="checked" class="far fa-check-square"></i>
            <i v-else class="far fa-square"></i>
        </b-td>
        <b-td class="pointer col-7" @click="openModal">
            <i v-if="notification.read_at === null" class="fas fa-envelope"></i>
            <i v-else class="fas fa-envelope-open-text"></i>
            {{notification.data.subject}}
        </b-td>
        <b-td class="col-sm-4 d-none d-sm-flex">{{notification.created_at}}</b-td>
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
            reset_watch: {
                type: Boolean,
                required: true,
            }
        },
        data() {
            return {
                checked: false,
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
            reset_watch()
            {
                if(this.reset_watch)
                {
                    this.checked = false;
                }
            }
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
            },
            toggleChecked()
            {
                this.checked = !this.checked;
                this.$emit('checked', this.checked, this.notification.id);
            },
        },
    }
</script>
