<template>
    <h5>Оставить комментарий</h5>
    <div class="alert alert-success" v-if="commentWasSend">
        Ваше сообщение успешно отправлено
    </div>
    <form @submit.prevent="submitHandler" v-else>
        <div class="mb-3">
            <input type="text" class="form-control" v-model="comment.subject" placeholder="Тема сообщения">
        </div>
        <div class="mb-3">
            <textarea class="form-control" v-model="comment.body" placeholder="Сообщение" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</template>

<script>
export default {
    props: ['articleId'],
    data() {
        return {
            comment: {
                article_id: this.articleId,
                body: '',
                subject: ''
            },
            commentWasSend: false
        }
    },
    methods: {
        submitHandler() {
            this.createComment();
        },
        async createComment() {
            await axios.post('/api/articles/comments', this.comment).then(response => {
                if (response.data.success) {
                    this.commentWasSend = true;
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
