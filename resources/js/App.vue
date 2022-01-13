<template>
    <the-navbar/>
    <main class="container mt-5 pt-5">
        <router-view/>
    </main>
    <the-footer/>
</template>

<script>
import TheNavbar from './components/TheNavbar'
import TheFooter from "./components/TheFooter";

export default {
    components: {TheFooter, TheNavbar}
}
</script>

<style>
html, body, #app {
    height: 100%;
}

body, #app {
    display: flex;
    flex-direction: column;
}

main {
    flex: 1 0 auto;
}
</style>
