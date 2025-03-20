<template>
    <el-card>
        <template #header>
            <div class="d-flex justify-content-between align-items-center">
                <h3>Гости</h3>
                <el-button type="primary" @click="dialogCreateGuest = true">Создать</el-button>
            </div>
        </template>
        <el-table :data="guests.data">
            <el-table-column type="index" label="#"></el-table-column>
            <el-table-column label="Фото" width="100">
                <template #default="scope">
                    <img :src=" '/'+scope.row.photo" class="img-rounded" v-if="scope.row.photo" alt=""
                         width="90" height="100">
                </template>
            </el-table-column>
            <el-table-column label="ФИО" prop="full_name"></el-table-column>
            <el-table-column label="Комания" prop="company_name"></el-table-column>
            <el-table-column label="Телефон" prop="phone_number"></el-table-column>

            <el-table-column label="Действия">
                <template #default="scope">
                    <el-button class="m-1" type="primary" size="small" @click="() => {
                        dialogUpdateGuest = true;
                        selectedGuest = scope.row
                    }">Редактировать
                    </el-button>
                    <el-button class="m-1" type="danger" size="small" @click="handleDeleteGuest(scope.row)">Удалить</el-button>
                    <el-button class="m-1" type="success" size="small" @click="() => {
                        dialogCreateInvite = true;
                        selectedGuest = scope.row
                    }">Приглашение
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination background layout="prev, pager, next"
                       :hide-on-single-page="guests.total <= guests.per_page"
                       class="mt-3"
                       @update:current-page="async (page) => changePage(page, () => handleGetGuests(page))"
                       :current-page="currentPage"
                       @update:page-size="() => {}"
                       :total="guests?.total" :page-size="guests?.per_page"/>
    </el-card>

    <create-guest-dialog v-model="dialogCreateGuest" v-if="dialogCreateGuest" @closeDialog="() => {
        dialogCreateGuest = false;
        handleGetGuests();
    }"></create-guest-dialog>
    <update-guest-dialog
        v-model="dialogUpdateGuest"
        :guest="selectedGuest"
        v-if="dialogUpdateGuest"
        @closeDialog="() => {
            dialogUpdateGuest = false;
            handleGetGuests();
        }"
    ></update-guest-dialog>

    <create-invite-dialog
        v-model="dialogCreateInvite"
        :guest="selectedGuest"
        v-if="dialogCreateInvite"
        @closeDialog="() => {
        dialogCreateInvite = false;
    }"></create-invite-dialog>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {ElMessageBox, ElNotification} from "element-plus";

import usePagination from "../hooks/usePagination";
import CreateGuestDialog from "../components/CreateGuestDialog.vue";
import UpdateGuestDialog from "../components/UpdateGuestDialog.vue";
import CreateInviteDialog from '../components/CreateInviteDialog.vue';

const guests = ref([])
const loading = ref(false)
const dialogCreateGuest = ref(false)
const dialogUpdateGuest = ref(false)
const dialogCreateInvite = ref(false)

const {currentPage, indexMethod, changePage} = usePagination()
const selectedGuest = ref();

const handleGetGuests = async (page = 1) => {
    loading.value = true
    const {data} = await axios.get('/api/visitor/guest/get-guests', {
        params: {
            page
        }
    })
    guests.value = data
    loading.value = false
}
const handleDeleteGuest = async (row) => {
    ElMessageBox.confirm('Are you sure to delete ')
        .then(async () => {
            const {data} = await axios.delete('/api/visitor/guest/' + row.id)
            if (data.success) {
                ElNotification({
                    type: 'success',
                    title: 'Success',
                    message: data.message
                })
                await handleGetGuests();
            }
            if (data.error) {
                ElNotification({
                    type: 'error',
                    title: 'Error',
                    message: data.message
                })
            }
        })
}

onMounted(() => {
    handleGetGuests();
})

</script>
