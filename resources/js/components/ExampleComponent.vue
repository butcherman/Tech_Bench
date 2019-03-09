<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        
                        <h2>Responses</h2>
                        <div class="card card-body" v-for="linklist in links" v-bind:key="linklist.id">
                            <h3>linklist.name</h3>
                            <h5>linklist.expire</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                links: [],
                linklist: {
                    id: '',
                    name: '',
                    num: '',
                    expire: '',
                    actions: ''
                },
                link_id: ''
            }
        },
        created() {
            this.fetchLinks();
        },
        methods: {
            fetchLinks() {
                fetch('/links/details/1')
                    .then(res => res.json())
                    .then(res => {
                    this.links = res;
                })
            }
        }
    }
</script>
