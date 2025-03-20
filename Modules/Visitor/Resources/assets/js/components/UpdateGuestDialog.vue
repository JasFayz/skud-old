<template>
    <el-dialog
        width="30%"
        destroy-on-close
        title="Создать гостя">
        <el-form label-position="top">
            <el-form-item label="Фамилия">
                <el-input v-model="updateGuestForm.last_name"></el-input>
            </el-form-item>
            <el-form-item label="Имя">
                <el-input v-model="updateGuestForm.first_name"></el-input>
            </el-form-item>
            <el-form-item label="Отчество">
                <el-input v-model="updateGuestForm.patronymic" placeholder="Отчество" name="patronymic"/>
            </el-form-item>
            <el-form-item label="Комания">
                <el-input v-model="updateGuestForm.company_name"></el-input>
            </el-form-item>
            <el-row gutter="10">
                <el-col :md="6">
                    <el-form-item label="Vip гость?">
                        <el-switch v-model="updateGuestForm.is_vip"/>
                    </el-form-item>
                </el-col>
                <el-col :md="18" v-if="!updateGuestForm.is_vip">
                    <el-form-item label="Номер пасспорта">
                        <el-input v-model="updateGuestForm.passport_number"/>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item label="Номер телефона">
                <el-input v-model="updateGuestForm.phone_number"></el-input>
            </el-form-item>
            <el-form-item label="Фото">
                <image-input v-model="updateGuestForm.photo" :image="updateGuestForm.currentPhoto"/>
            </el-form-item>
        </el-form>

        <template #footer>
            <el-button @click="emit('closeDialog')">Отменить</el-button>
            <el-button type="primary" @click="handleUpdateGuest">Обновить</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import {reactive, onMounted} from "vue";
import {ElNotification} from "element-plus";
import ImageInput from '../components/ImageInput.vue'

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    guest: Object
})
const updateGuestForm = reactive({
    first_name: props.guest.first_name ?? '',
    last_name: props.guest.last_name ?? '',
    company_name: props.guest.company_name ?? '',
    phone_number: props.guest.phone_number ?? '',
    photo: '',
    currentPhoto: props.guest.photo,
    is_vip: props.guest.is_vip,
    passport_number: props.guest.passport_number
})

const handleUpdateGuest = async () => {
    const form = new FormData()
    Object.entries(updateGuestForm).forEach(entry => {
        const [key, value] = entry;
        form.append(key, value)
    })
    form.append('_method', 'put')
    axios.post('/api/visitor/guest/' + props.guest.id, form, {
        headers: {
            'Content-Type': 'form-data'
        }
    }).then((response) => {
        if (response.data.success) {
            ElNotification({
                type: 'success',
                title: 'Success',
                message: response.data.message
            })
            emit('closeDialog')
        }
    }).catch((error) => {
        ElNotification({
            type: 'error',
            title: 'Error',
            message: error.response.data.message
        })
    });

}

onMounted(() => {

})

</script>

