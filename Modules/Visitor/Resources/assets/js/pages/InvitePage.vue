<template>
    <el-card>
        <template #header>
            <div class="d-flex justify-content-between align-items-center">
                <h3>Приглашения</h3>

            </div>
        </template>
        <el-table :data="invites.data">
            <el-table-column type="index" label="#"></el-table-column>
            <el-table-column label="Имя гостя" prop="guest.full_name"></el-table-column>
            <el-table-column label="Начать" prop="start"></el-table-column>
            <el-table-column label="Конец" prop="end"></el-table-column>
            <el-table-column label="Ответственной лицо" prop="responsible_user.name"></el-table-column>
            <el-table-column label="Цель визита" prop="target_user.name"></el-table-column>
            <el-table-column label="Фото">
                <template #default="scope">
                    <el-icon :size="24" color="green" v-if="scope.row.guest.photo">
                        <CircleCheck/>
                    </el-icon>
                    <el-icon :size="24" color="red" v-else>
                        <CircleClose/>
                    </el-icon>
                </template>
            </el-table-column>
            <el-table-column label="Подтвержден" v-if="can('invite.approve')">
                <template #default="scope">
                    <el-icon :size="24" color="green" v-if="scope.row.is_approved">
                        <CircleCheck/>
                    </el-icon>
                    <el-icon :size="24" color="red" v-else>
                        <CircleClose/>
                    </el-icon>
                </template>
            </el-table-column>
            <el-table-column label="Действия">
                <template #default="scope">
                    <el-popconfirm
                        width="220"
                        confirm-button-text="Да"
                        cancel-button-text="Нет"
                        icon-color="#626AEF"
                        title="Вы точно хотите удалить?"
                        @confirm="handleDeleteInvite(scope.row)"
                    >
                        <template #reference>
                            <el-button type="danger" size="small">Удалить
                            </el-button>
                        </template>
                    </el-popconfirm>

                    <el-button type="primary" size="small" v-if="can('invite.attach-photo') && !scope.row.guest.photo" @click="() => {
                        dialogAttachImage = true;
                        selectedInvite = scope.row
                    }">
                        Загрузить фото
                    </el-button>

                    <el-button type="success" size="small" v-if="can('invite.approve')"
                               @click="approveInvite(scope.row)">
                        Подтверждение
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>

    <attach-guest-image-dialog v-model="dialogAttachImage" :invite="selectedInvite" @imageAttached="handleGetInvite"></attach-guest-image-dialog>

</template>

<script setup>
import {ref, onMounted} from 'vue';
import {ElNotification} from "element-plus";
import {CircleCheck, CircleClose} from '@element-plus/icons-vue'
import AttachGuestImageDialog from "../components/AttachGuestImageDialog.vue";

const dialogAttachImage = ref(false)
const invites = ref([])
const loading = ref(false)
const handleGetInvite = async () => {
    loading.value = true
    const {data} = await axios.get('/api/visitor/invite/get-invites')
    invites.value = data
    loading.value = false
}
const handleDeleteInvite = async (row) => {
    const {data, error} = await axios.delete('/api/visitor/invite/' + row.id)

    if (data.success) {
        ElNotification({
            type: 'success',
            title: 'Success',
            message: data.message
        })
        await handleGetInvite()
    }
    if (data.error) {
        ElNotification({
            type: 'error',
            title: 'Error',
            message: data.error
        })
    }
}

const approveInvite = (row) => {
    axios.post('/api/visitor/invite/' + row.id + '/approve')
        .then((response) => {
            if (response.data.success) {
                ElNotification({
                    type: 'success',
                    title: 'Success',
                    message: response.data.message
                })
                handleGetInvite()
            }

        })
}
const selectedInvite = ref('');

onMounted(() => {
    handleGetInvite()
})

</script>

<style scoped>

</style>
