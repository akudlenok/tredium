<template>
    <article-tag class='me-1 mb-2' v-for="tag in tags" :tag="tag"/>
</template>

<script>
import ArticleTag from "./ArticleTag";

export default {
    props: {
        tags: {
            type: Array
        }
    },
    components: {ArticleTag}
}
</script>

<style scoped>

</style>
