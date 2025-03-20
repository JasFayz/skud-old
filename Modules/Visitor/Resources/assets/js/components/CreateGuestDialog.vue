<template>
    <el-dialog
        width="30%"
        destroy-on-close
        title="Создать гостя">
        <el-form label-position="top">
            <el-form-item label="Фамилия">
                <el-input v-model="createGuestForm.last_name"></el-input>
            </el-form-item>
            <el-form-item label="Имя">
                <el-input v-model="createGuestForm.first_name"></el-input>
            </el-form-item>
            <el-form-item label="Комания">
                <el-input v-model="createGuestForm.company_name"></el-input>
            </el-form-item>
            <el-form-item label="Номер телефона">
                <el-input v-model="createGuestForm.phone_number"></el-input>
            </el-form-item>
            <el-form-item label="Фото">
                <image-input v-model="createGuestForm.photo"/>
            </el-form-item>
        </el-form>

        <template #footer>
            <el-button @click="emit('closeDialog')">Отменить</el-button>
            <el-button type="primary" @click="handleCreateGuest">Создать</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import {reactive} from "vue";
import {ElNotification} from "element-plus";
import ImageInput from '../components/ImageInput.vue'

const emit = defineEmits(['closeDialog'])

const createGuestForm = reactive({
    first_name: '',
    last_name: '',
    company_name: '',
    phone_number: '',
    photo: ''
})


const handleCreateGuest = async () => {
    const form = new FormData()
    Object.entries(createGuestForm).forEach(entry => {
        const [key, value] = entry;
        form.append(key, value)
    })

    axios.post('/api/visitor/guest', form, {
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

</script>

<style scoped>

</style>
