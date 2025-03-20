import {createApp, ref} from 'vue'

import axios from 'axios';

window.axios = axios;

const csrf_token = document.querySelector('meta[name=csrf-token]').getAttribute('content')
// const authUser = document.querySelector('meta[name=authUser]').getAttribute('content')

import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'

import ElementUI from 'element-plus';
import 'element-plus/dist/index.css';
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
// import commonConfig from '../../../../Common/Resources/assets/js/commonConfig';

import {PageSidebar, PageHeader, PageFooter} from '../../../../../resources/js/partials/layout-partial'

import GuestPage from './pages/GuestPage.vue';
import InvitePage from './pages/InvitePage.vue';
import GuestList from './pages/Admin/Guest/GuestList.vue';
import GuestInfo from './pages/Admin/Guest/GuestInfo.vue';
import InviteList from './pages/Admin/Inivte/InviteList.vue';
import InviteInfo from './pages/Admin/Inivte/InviteInfo.vue';

const app = createApp({
    components: {
        PageHeader,
        PageSidebar,
        PageFooter,
        GuestPage,
        InvitePage,
        GuestList,
        GuestInfo,
        InviteList,
        InviteInfo
    }
})
// app.provide('csrf_token', csrf_token)
// app.provide('commonConfig', commonConfig)
app.use(ElementUI)
app.use(LaravelPermissionToVueJS)
app.mount('#app')

for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
}
