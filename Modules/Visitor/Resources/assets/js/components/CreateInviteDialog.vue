<template>
    <el-dialog
        title="Приглашение"
        destroy-on-close
        width="30%">
        <el-form label-position="top">
            <el-row :gutter="10">
                <el-col :md="12">
                    <el-form-item label="Начало">
                        <el-date-picker
                            class="w-100"
                            v-model="start"
                            :localTime="false"
                            type="datetime"
                            placeholder="Начало"
                            @change="changeStartDate"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Конец">
                        <el-date-picker
                            class="w-100"
                            v-model="end"
                            type="datetime"
                            placeholder="Конец"
                            @change="changeEndDate"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-form-item label="Ответственное лицо">
                <el-select v-model="createForm.responsible_user"
                           filterable
                           class="w-100">
                    <el-option v-for="staff in staffs"
                               :label="staff.name"
                               :key="staff.id"
                               :value="staff.id"/>

                </el-select>
            </el-form-item>
            <el-form-item label="К кому пришел">
                <el-select v-model="createForm.target_user"
                           filterable
                           class="w-100">
                    <el-option v-for="staff in staffs"
                               :label="staff.name"
                               :key="staff.id"
                               :value="staff.id"/>
                </el-select>
            </el-form-item>
            <el-form-item label="Комментарий">
                <el-input type="textarea" v-model="createForm.comment" placeholder="Комментарий"></el-input>
            </el-form-item>

            <el-divider content-position="center">Зоны</el-divider>


            <el-checkbox v-for="zone in allZones"
                         v-if="allZones"
                         :key="zone.id"
                         v-loading="zoneLoading"
                         v-model="createForm.zones[zone.id]"
                         :label="zone.id" size="large" border>
                {{ zone.name }}
            </el-checkbox>

        </el-form>

        <template #footer>
            <el-button @click="emit('closeDialog')">Отменить</el-button>
            <el-button type="primary" @click="handleCreateInvite">Создать</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import {reactive, onMounted, ref} from 'vue';
import {ElNotification} from "element-plus";
import moment from "moment";

const props = defineProps({
    guest: Object
})
const start = ref('')
const end = ref('')


const createForm = reactive({
    start: '',
    end: '',
    responsible_user: '',
    target_user: '',
    zones: {},
    comment: '',
    guest_id: props.guest.id
})
const emit = defineEmits(['closeDialog'])
const zoneLoading = ref(false)
const staffs = ref([]);
const allZones = ref([]);

const handleGetUsers = async () => {
    const {data} = await axios.get('/api/admin/user/get-all')
    staffs.value = data
}
const handleGetZones = async (filter) => {
    zoneLoading.value = true
    const {data} = await axios.get('/api/admin/zone/get-all', {
        params: {...filter}
    })
    allZones.value = data
    zoneLoading.value = false
}

const handleCreateInvite = async () => {
    const {data, error} = await axios.post('/api/visitor/invite', createForm)
    if (data.success) {
        ElNotification({
            type: 'success',
            title: 'Success',
            message: data.message
        })
        emit('closeDialog')
    }
    if (!data.success) {
        ElNotification({
            type: 'error',
            title: 'Error',
            message: data.message
        })
    }
}
const changeStartDate = (val) => {
    createForm.start = moment(val).format('YYYY-MM-DD HH:mm:ss')
}
const changeEndDate = (val) => {
    createForm.end = moment(val).format('YYYY-MM-DD HH:mm:ss')
}

onMounted(async () => {
    await handleGetUsers();
    await handleGetZones({zone_type: 'private'})
})


</script>
