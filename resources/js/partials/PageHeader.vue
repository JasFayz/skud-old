<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

        </ul>

        <h3 class="mx-auto">{{ authUser.company?.name }}</h3>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-none d-sm-inline-block">
                <form action="/logout" METHOD="POST" class="m-0">
                    <input type="hidden" name="_token" :value="csrf_token">
                    <button class="btn nav-link">
                        <span class="mr-2">Logout</span>
                        <i class="fas fa-door-open"></i>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</template>

<script setup>
import {inject} from "vue";

const csrf_token = inject('csrf_token')

const props = defineProps({
    authUser: Object
})
</script>

<style scoped>

</style>
