<template>
    <i class="fa-bookmark pointer" :class="isFav" @click="toggleFav"></i>
</template>

<script>
    export default {
        props: {
            is_fav: {
                type: Boolean,
                default: false,
            },
            toggle_route: {
                type: String,
                required: true,
            },
            bookmark_id: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                checked: this.is_fav,
            }
        },
        computed: {
             isFav()
             {
                 if(this.loading)
                 {
                     return 'spinner-grow text-light'
                 }
                 return this.checked ? 'fas bookmark-checked' : 'far bookmark-unchecked';
             }
        },
        methods: {
            toggleFav()
            {
                this.checked = !this.checked;
                this.loading = true;
                axios.get(this.route(this.toggle_route, [this.checked, this.bookmark_id]))
                    .then(res => {
                        this.loading = false;
                    }).catch(error => this.bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
            }
        }
    }
</script>
