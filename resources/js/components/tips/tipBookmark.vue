<template>
    <i :class="bookmark_class" :title="bookmark_title" v-b-tooltip.hover @click="toggleFav"></i>
</template>

<script>
    export default {
        props: {
            is_fav: {
                type: Boolean,
                required: true,
            },
            tip_id: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                fav: this.is_fav,
                loading: false,
            }
        },
        computed: {
            bookmark_class()
            {
                if(this.loading)
                {
                    return 'spinner-grow text-light';
                }

                return this.fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.fav ? 'Remove from Favorites' : 'Add to Favorites';
            },
        },
        methods: {
            toggleFav()
            {
                this.loading = true;
                axios.get(this.route('tips.toggle_fav', this.tip_id))
                    .then(res => {
                        this.fav = res.data.favorite;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        }
    }
</script>
