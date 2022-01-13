<template>
    <div class="row">
        <div class="col">
            <h1>Успех</h1>
            <p>Для молодых и успешных</p>
        </div>
    </div>
    <loader v-if="loading"/>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" v-else>
        <div class="col" v-for="article in articles">
            <article-card :article=article :auto-height=true />
        </div>
    </div>
</template>

<script>
import ArticleCard from "../components/ArticleCard";
import Loader from "../components/Loader";

export default {
    components: {ArticleCard, Loader},
    data() {
        return {
            articles: [],
            loading: false
        }
    },
    methods: {
        async getArticles() {
            this.loading = true
            const {data} = await axios.get('/api/articles?per_page=6');
            this.articles = data.data;
            this.loading = false;
        }
    },
    created() {
        this.getArticles()
    }
}
</script>
