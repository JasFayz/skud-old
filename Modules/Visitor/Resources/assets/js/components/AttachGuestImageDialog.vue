<template>
    <el-dialog
        width="30%"
        title="Прикрепить фото гостя"
        v-loading="loading">
        <el-upload
            class="avatar-uploader"
            :show-file-list="false"
            :on-change="handleAvatarSuccess"
            :auto-upload="false">
            <img v-if="imageUrl" :src="imageUrl" class="avatar"/>
            <el-icon v-else class="avatar-uploader-icon">
                <Camera/>
            </el-icon>
        </el-upload>

        <template #footer>
            <el-button type="primary" @click="handleAttachImage">
                Отправить
            </el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import {ref} from 'vue';
import {Camera} from '@element-plus/icons-vue'
import {ElNotification} from "element-plus";

const imageUrl = ref('');
const image = ref('')
const props = defineProps({
    invite: Object
})
const emit = defineEmits(['imageAttached'])
const loading = ref(false)

const handleAvatarSuccess = (uploadFile) => {
    image.value = uploadFile
    imageUrl.value = URL.createObjectURL(uploadFile.raw)
}
const handleAttachImage = () => {
    loading.value = true
    axios.post('/api/visitor/invite/' + props.invite.id + '/attach-image', image.value, {
        headers: {
            "Content-Type": 'multipart/form-data'
        }
    }).then((response) => {
        response.data.terminalResponse.map((res, index) => {
            if (res.status.success) {
                setTimeout(() => {
                    ElNotification({
                        type: 'success',
                        title: 'Success',
                        dangerouslyUseHTMLString: true,
                        message: res.status.msg,
                    })
                }, index * 100)
                emit('imageAttached')
            } else {
                setTimeout(() => {
                    ElNotification({
                        type: 'error',
                        title: 'Error',
                        message: res.status.msg
                    })
                }, index * 100)
            }
        })
    }).finally(() => {
        loading.value = false
    })
}

</script>
<style scoped>
.avatar-uploader .avatar {
    width: 178px;
    height: 178px;
    display: block;
}
</style>

<style>
.avatar-uploader .el-upload {
    border: 1px dashed var(--el-border-color);
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: var(--el-transition-duration-fast);
    justify-content: center;
}

.avatar-uploader .el-upload:hover {
    border-color: var(--el-color-primary);
}

.el-icon.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    text-align: center;
}
</style>
