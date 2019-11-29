<template>
    <div>
        <div class="row">
            <div class="col-md-8 grid-margin">
                <h3>
                    <span :class="classFav" :title="markFav" v-b-tooltip.hover @click="toggleFav"></span>
                    {{tip_details.subject}}
                </h3>
                <div class="tip-details">
                    <span><strong>ID:</strong>  {{tip_details.tip_id}}</span>
                    <span><strong>Created:</strong>  {{tip_details.created_at}}</span>
                    <span><strong>Updated:</strong>  {{tip_details.updated_at}}</span>
                </div>
                <div class="tip-details">
                    <span><strong>Tags:</strong>
                        <b-badge pill variant="primary" v-for="sys in tip_details.system_types" :key="sys.sys_id" class="ml-1 mb-1">{{sys.name}}</b-badge>
                    </span>
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="float-right">
                    <a :href="route('tip.downloadTip', tip_details.tip_id)" class="btn btn-primary btn-block" title="Download As PDF" v-b-tooltip:hover>Download Tip</a>
                    <a :href="route('tips.edit', tip_details.tip_id)" class="btn btn-warning btn-block" title="Edit this Tip" v-b-tooltip:hover v-show="can_edit">Edit Tip</a>
                    <button class="btn btn-danger btn-block" v-show="can_del" @click="deleteTip">Delete Tip</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body tip-description" v-html="tip_details.description"></div>
                </div>
            </div>
        </div>
        <b-modal id="loading-modal" size="sm" ref="loading-modal" hide-footer hide-header hide-backdrop centered>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'tip_details',
        'is_fav',
        'can_edit',
        'can_del',
    ],
    data() {
        return {
            isFav:    this.is_fav,
            classFav: this.is_fav ? 'ti-bookmark-alt bookmark-checked' : 'ti-bookmark bookmark-unchecked',
            markFav:  this.is_fav ? 'Remove From Favorites' : 'Add to Favorites',
        }
    },
    created()
    {
        console.log(this.tip_details);
    },
    methods: {
        toggleFav()
        {
            //
            this.classFav = 'spinner-grow text-light';
            if(this.isFav)
            {
                axios.get(this.route('tip.toggle-fav', ['remove', this.tip_details.tip_id]))
                    .then(res => {
                        this.isFav = false;
                        this.classFav  = 'ti-bookmark bookmark-unchecked';
                        this.markFav = 'Add To Favorites'; //  : 'Add to Favorites',
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
            else
            {
                axios.get(this.route('tip.toggle-fav', ['add', this.tip_details.tip_id]))
                    .then(res => {
                        this.isFav = true;
                        this.classFav  = 'ti-bookmark-alt bookmark-checked';
                        this.markFav = 'Remove From Favorites';
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        deleteTip()
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to deactivate this customer.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {
                if(value)
                {
                    this.$refs['loading-modal'].show();
                    axios.delete(this.route('tips.destroy', this.tip_details.tip_id))
                    .then(res => {
                        console.log(res);
                        window.location.href = this.route('tips.index');
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            })
            .catch(error => {
                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
            });
        }
    }
}
</script>
