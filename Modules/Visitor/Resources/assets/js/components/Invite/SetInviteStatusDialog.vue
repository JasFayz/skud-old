<template>
    <el-dialog
        title="Подтверждение приглашения"
        width="30%">
        <el-form label-position="top">
            <el-form-item label="Статус">
                <el-radio-group v-model="status">
                    <el-radio-button :key="1" :id="1" label="1">Подтвердить</el-radio-button>
                    <el-radio-button :key="2" :id="2" label="2">Отклонить</el-radio-button>
                    <el-radio-button :key="3" :id="3" label="3">Завершить</el-radio-button>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="Коммент">
                <el-input type="textarea" v-model="comment"/>
            </el-form-item>

        </el-form>
        <template #footer>
            <el-button @click="emit('closeDialog')"> Отменить</el-button>

            <el-button type="success" @click="handleSetStatus" :disable="loading">Подтвердить</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import {ref} from 'vue';
import {ElNotification} from "element-plus";

const emit = defineEmits(['closeDialog', 'inviteStatusChanged', 'responseTerminal'])
const props = defineProps(['invite'])
const status = ref('');
const comment = ref('')
const loading = ref(false)
const handleSetStatus = async () => {
    loading.value = true
    const {data, error} = await axios.post('/api/visitor/invite/' + props.invite.id + '/set-status', {
        status: status.value,
        comment: comment.value
    })

    loading.value = false;
    emit('inviteStatusChanged', data.responseTerminal);
    emit('closeDialog');
}
</script>

<style scoped>

</style>
