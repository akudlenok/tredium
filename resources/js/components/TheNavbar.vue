<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <router-link class="navbar-brand" to="/">Тредиум</router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto me-0 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/">Главная страница</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/articles" custom v-slot="{ navigate, href }">
                            <a href="#"
                               @click="navigate"
                               class="nav-link"
                               :class="{active: $route.path.indexOf(href) !== -1}">
                                Каталог статей
                            </a>
                        </router-link>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {}
</script>
