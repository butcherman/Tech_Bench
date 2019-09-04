<template>
    <div class="clearfix">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="pb-2 mt-4 mb-2 border-bottom text-center"><h1>Tech Tips</h1></div>
            </div>
        </div>
        <b-form @submit="searchTips" id="tech-tip-search-form">
            <b-input-group size="lg">
                <b-form-input placeholder="Search Tech Tips..." autofocus v-model="form.searchText"></b-form-input>
                <b-input-group-append>
                    <b-button type="submit" variant="info"><i class="fa fa-search" aria-hidden="true"></i> Search</b-button>
                </b-input-group-append>
            </b-input-group>
            <b-navbar toggleable="lg" id="tech-tip-sidebar">
                <div class="border-bottom w-100">
                    <b-navbar-brand>Filter Options</b-navbar-brand>
                    <b-navbar-toggle target="filter-collapse" class="float-right"></b-navbar-toggle>
                </div>
                <b-collapse id="filter-collapse" class="mt-3" is-nav>
                    <b-form-group label="Article Type:">
                        <b-form-checkbox-group
                        id="article-type-checkbox"
                        v-model="form.articleType"
                        :options="JSON.parse(filter_types)"
                        name="articleType"
                        stacked
                        ></b-form-checkbox-group>
                    </b-form-group>
                    <b-form-group label="System Type:">
                        <b-form-checkbox-group
                        id="system-type-checkbox"
                        v-model="form.systemType"
                        :options="JSON.parse(system_types)"
                        name="systemType"
                        stacked
                        ></b-form-checkbox-group>
                    </b-form-group>
                </b-collapse>
            </b-navbar>
        </b-form>
        <div id="tech-tip-content">
            <div class="text-center border-bottom pb-3 mb-3">
               <a :href="tips_route+'/create'" class="text-center btn btn-info">Create New Tech Tip</a>
            </div>
            <div v-for="tip in tips" class="tip-results-wrapper">
                <h3><a :href="tip.url">{{tip.title}}</a></h3>
                <h6><small>{{tip.updated}}</small></h6>
                <div class="tip-details-wrapper">
                    <div class="tip-results-details" v-html="tip.description"></div>
                </div>
            </div>
            
            
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'tips_route',
        'search_route',
        'filter_types',
        'system_types',
    ],
    data () {
        return {
            form: {
                searchText: '',
                articleType: [],
                systemType: [],
            },
            tips: [],
        }
    },
    created()
    {
        console.log('loaded');
        this.getTips();
    },
    methods: {
        searchTips(e)
        {
            e.preventDefault();
//            console.log(this.form.searchText);
            this.getTips();
            
        },
        getTips()
        {
            axios.post(this.search_route, this.form)
                .then(res => {
                    this.tips = res.data;
                    console.log(res);
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
        }
    }
}
</script>
