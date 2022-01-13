<template>
    <router-link class="nav-link badge bg-primary" :to="{ path: `/tags/${tag.id}`}">
        {{ tag.name }}
    </router-link>
</template>

<script>
export default {
    props: ['tag']
}
</script>

<style scoped>

</style>
