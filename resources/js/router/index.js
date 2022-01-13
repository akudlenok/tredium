import {createRouter, createWebHistory} from 'vue-router'
import Home from "../views/Home";
import Articles from "../views/Articles";
import Article from "../views/Article";
import Tag from "../views/Tag";

const routes = [
    {path: '/', component: Home, name: 'home'},
    {path: '/articles', component: Articles, name: 'articles'},
    {path: '/articles/:id(\\d+)', component: Article, name: 'article'},
    {path: '/tags/:id(\\d+)', component: Tag, name: 'tag'}
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
})

export default router
