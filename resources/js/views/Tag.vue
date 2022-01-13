<template>
    <loader v-if="loading"/>
    <div class="container" v-else>
        <div class="row">
            <h1>{{ tag.name.toUpperCase() }}</h1>
            <div class="list-group list-group-flush">
                <router-link class="list-group-item list-group-item-action"
                             v-for="article in tag.articles"
                             :to="{ path: `/articles/${article.id}`}">
                    {{ article.title }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import Loader from "../components/Loader";

export default {
    components: {Loader},
    data() {
        return {
            loading: false,
            tag: {}
        }
    },
    methods: {
        async getTag() {
            this.loading = true;
            const {data} = await axios.get(`/api/articles/tags/${this.$route.params.id}`);
            this.tag = data;
            this.loading = false;
        }
    },
    created() {
        this.getTag();
    }
}
</script>

<style scoped>

</style>
