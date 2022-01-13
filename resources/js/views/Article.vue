<template>
    <loader v-if="loading"/>
    <div class="container" v-else>
        <div class="row">
            <h1>{{ article.title }}</h1>
            <div class="d-flex align-items-center py-3">
                <div class="d-flex justify-content-center align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye"
                         viewBox="0 0 16 16">
                        <path
                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"></path>
                        <path
                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"></path>
                    </svg>
                    <span class="mx-3">{{ article.view_count || 0 }}</span>
                </div>
                <button type="button"
                        @click="like"
                        :class="{liked}"
                        class="d-flex justify-content-center align-items-center btn btn-outline-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-heart" viewBox="0 0 16 16">
                        <path
                            d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"></path>
                    </svg>
                    <span class="visually-hidden">Like</span>
                    <span class="ms-2">{{ article.like_count || 0 }}</span>
                </button>
            </div>
            <div v-if="article.tags">
               <article-tags-list :tags="article.tags" />
            </div>
            <p>{{ article.content }}</p>
            <img :src="article.img_path">
            <div class="my-3">
                <hr>
            </div>
            <article-comment-form :article-id="article.id"/>
        </div>
    </div>
</template>

<script>
import Loader from "../components/Loader";
import ArticleCommentForm from "../components/ArticleCommentForm";
import ArticleTag from "../components/ArticleTag";
import ArticleTagsList from "../components/ArticleTagsList";

export default {
    components: {ArticleTagsList, ArticleCommentForm, Loader},
    data() {
        return {
            loading: false,
            article: {},
            liked: false,
            timing: null,
        }
    },
    methods: {
        async getArticle() {
            this.loading = true;
            const {data} = await axios.get(`/api/articles/${this.$route.params.id}`);
            this.article = data.article;
            this.liked = data.is_liked;
            this.timing = setTimeout(this.articleHasViewed, 5000);
            this.loading = false;
        },
        async like() {
            const {data} = await axios.post(`/api/articles/${this.$route.params.id}/like`);
            this.article.like_count = data.like_count;
            this.liked = !data.is_can_liked;
        },
        async articleHasViewed() {
            const {data} = await axios.post(`/api/articles/${this.$route.params.id}/viewed`);
            this.article.view_count = data.view_count;
        }
    },
    created() {
        this.getArticle();
    },
    beforeUnmount() {
        clearTimeout(this.timing);
    }
}
</script>

<style scoped>
img {
    width: 640px;
}

.liked {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>
