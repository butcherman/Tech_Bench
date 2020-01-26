<template>
    <button class="btn btn-sm btn-danger" title="Delete Log" v-b-tooltip:hover @click="deleteLog">
        <i class="fas fa-trash"></i>
    </button>
</template>

<script>
export default {
    props: [
        'date',
    ],
    methods: {
        deleteLog()
        {
            this.$bvModal.msgBoxConfirm('Please confirm you want to delete this log.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                }).then(res => {
                    if(res)
                    {
                        axios.delete(this.route('log-viewer::logs.delete', {date: this.date}))
                            .then(res => {
                                console.log(res);
                                // location.reload();
                                window.location.href = this.route('log-viewer::logs.list');
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }
                });
        }
    }
}
</script>
