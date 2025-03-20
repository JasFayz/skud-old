<template>
    <footer class="main-footer position-fixed " style="z-index: 9999;bottom: 0;left: 0;right: 0">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{ moment().format('Y') }}</strong> All rights
        reserved.
    </footer>
</template>

<script setup>
import moment from "moment";
</script>
