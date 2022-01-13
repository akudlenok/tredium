<template>
    <div class="card shadow-sm" :class="{ 'h-100': autoHeight }">
        <img :src="article.img_path" class="card-img-top">
        <div class="card-body">
            <router-link :to="{ path: `/articles/${article.id}`}">
                <h5 class="card-title">{{ article.title }}</h5>
            </router-link>
            <p class="card-text">{{ article.content }}</p>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        article: {
            type: Object
        },
        autoHeight: {
            type: Boolean,
            default: false
        },
        showActions: {
            type: Boolean,
            default: false
        }
    }
}
</script>
