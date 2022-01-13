<template>
    <nav>
        <ul class="pagination">
            <li :class="{ disabled: pagination.current_page === 1 }"
                class="page-item">
                <button class="page-link"
                        @click="onChangePage(1)">
                    В начало
                </button>
            </li>

            <li class="page-item pagination-page-nav"
                v-for="(page, key) in pageRange"
                :key="key"
                :class="{ 'active': page === pagination.current_page }">
                <span class="page-link"
                      v-if="page === pagination.current_page">
                    {{ page }}
                </span>
                <button class="page-link"
                        v-else
                        @click="onChangePage(page)">
                    {{ page }}
                </button>
            </li>

            <li :class="{ disabled: pagination.current_page === pagination.total_pages }"
                class="page-item">
                <button class="page-link"
                        @click="onChangePage(pagination.total_pages)">
                    В конец
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    emits: ['on-change'],
    props: {
        pagination: {
            type: Object,
            default: {}
        }
    },
    methods: {
        onChangePage(page) {
            this.$emit('on-change', page);
        }
    },
    computed: {
        pageRange() {
            let current = this.pagination.current_page;
            let last = this.pagination.total_pages;
            let delta = this.pagination.limit;
            let left = current - delta;
            let right = current + delta + 1;
            let range = [];
            let pages = [];
            let l;
            for (let i = 1; i <= last; i++) {
                if (i === 1 || i === last || (i >= left && i < right)) {
                    range.push(i);
                }
            }
            range.forEach(function (i) {
                if (l) {
                    if (i - l === 2) {
                        pages.push(l + 1);
                    } else if (i - l !== 1) {
                        pages.push('...');
                    }
                }
                pages.push(i);
                l = i;
            });
            return pages;
        }
    }
}
</script>

<style scoped>

</style>
