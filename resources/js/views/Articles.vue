<template>
    <loader v-if="loading"/>
    <div class="row row-cols-1 row-cols-md-2 g-4" v-else>
        <div class="col" v-for="article in articles">
            <article-card :article=article
                          :show-actions=true />
        </div>
        <pagination :pagination="pagination" @on-change="getArticles"/>
    </div>

</template>

<script>
import ArticleCard from "../components/ArticleCard";
import Loader from "../components/Loader";
import Pagination from "../components/Pagination";

export default {
    components: {Pagination, ArticleCard, Loader},
    data() {
        return {
            loading: false,
            articles: [],
            pagination: {},
        }
    },
    methods: {
        async getArticles(page) {
            this.loading = true;
            const {data} = await axios.get(`/api/articles?page=${page}`);
            const {meta} = data;
            this.articles = data.data;
            this.makePagination(meta);
            await this.$router.push({query: {page: meta.current_page}});
            this.loading = false
        },
        makePagination(meta) {
            let {current_page, total_pages, limit} = meta;
            this.pagination = {current_page, total_pages, limit};
        }
    },
    created() {
        const page = this.$route.query.page || 1;
        this.getArticles(page);
    }
}
</script>

<style scoped>

</style>
