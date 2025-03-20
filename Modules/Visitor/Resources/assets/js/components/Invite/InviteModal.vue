<template>
    <el-dialog
        :title="title">
        <el-form label-position="top">
            <el-row :gutter="10">

                <el-col :md="12">
                    <el-form-item label="Start" :error="errors.start">
                        <el-date-picker
                            class="w-100"
                            v-model="form.start"
                            :localTime="false"
                            type="datetime"
                            :default-time="defaultStartTime"
                            :disabled-date="disabledDate"
                            placeholder="Начало"
                            @change="changeStartDate"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="End" :error="errors.end">
                        <el-date-picker
                            class="w-100"
                            v-model="form.end"
                            type="datetime"
                            placeholder="Конец"
                            :disabled-date="disabledDate"
                            :default-time="defaultEndTime"
                            @change="changeEndDate"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>

                <el-col :md="12">
                    <el-form-item label="Target User" :error="errors.target_user">
                        <el-select v-model="form.target_user" clearable filterable class="w-100">
                            <el-option v-for="user in users"
                                       :value="user.id"
                                       :label="user.name"
                            />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Responsible User" :error="errors.responsible_user">
                        <el-select v-model="form.responsible_user" clearable filterable class="w-100">
                            <el-option v-for="user in users"
                                       :value="user.id"
                                       :label="user.name"
                            />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Company" :error="errors.company_id">
                        <el-select v-model="form.company_id" class="w-100">
                            <el-option v-for="company in companies"
                                       :value="company.id"
                                       :label="company.name"/>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Guest" v-if="!guest">
                        <el-select v-model="form.guest" class="w-100">
                            <option v-for="guest in guests"
                                    :value='guest.id'
                                    :label="guest.full_name"/>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :md="24">
                    <el-checkbox-group v-model="form.terminals">
                        <el-table :data="zones">
                            <el-table-column prop="name" label="Zone" align="left"/>
                            <el-table-column label="Terminals">
                                <template #default="props">
                                    <el-checkbox v-for="terminal in props.row.terminals"
                                                 :key="terminal.id"
                                                 :label="terminal.id">
                                        {{ terminal.name }}
                                    </el-checkbox>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-checkbox-group>
                </el-col>
            </el-row>
        </el-form>
        <template #footer>
            <el-button @click="() => {
                emit('closeDialog')
            }">Cancel
            </el-button>
            <el-button type="primary" @click="handleSubmit">Save</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import {ref} from 'vue';
import useNotification from "../../../../../../Admin/Resources/assets/hooks/useNotification";
import moment from "moment/moment";

const props = defineProps({
    model: Object,
    users: Array,
    title: String,
    zones: Array,
    guest: Object,
    guests: Array,
    companies: Array
})
const errors = ref({})
const emit = defineEmits(['closeDialog'])
const form = ref({
    start: props?.model?.start,
    end: props?.model?.end,
    target_user: props?.model?.target_user_id,
    responsible_user: props?.model?.responsible_user_id,
    terminals: props.model?.terminals.map(t => t.id),
    guest_id: props.guest?.id,
    company_id: props.model?.company_id
})
const {notify} = useNotification()

const onMaska = (event) => {
    form.passport_number = event.detail.masked.toUpperCase()
}
const defaultStartTime = moment().hour(9).minute(0).second(0).toDate()
const defaultEndTime = moment().hour(18).minute(0).second(0).toDate()
const changeStartDate = (val) => {
    form.value.start = moment(val).format('YYYY-MM-DD HH:mm:ss')
}
const changeEndDate = (val) => {
    form.value.end = moment(val).format('YYYY-MM-DD HH:mm:ss')
}
const disabledDate = (time) => {
    return time.getTime() < moment().subtract('1', 'day')
}
const handleSubmit = async () => {
    if (props.model) {
        axios.put(route('admin.visitor.invites.update', props.model), form.value)
            .then(res => {
                notify('success', res.data.message)
                emit('closeDialog')
            })
            .catch(e => {
                errors.value = e.response.data.errors
            });
    } else {
        await axios.post(route('admin.visitor.invites.store'), form.value)
            .then(res => {
                notify('success', res.data.message)
                emit('closeDialog')
            })
            .catch(e => {
                errors.value = e.response.data.errors
            });

    }
}
</script>

