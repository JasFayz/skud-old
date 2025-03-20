<template>
    <el-dialog
        :title="title">
        <el-form label-position="top">
            <el-row :gutter="10">
                <el-col :md="8">
                    <el-form-item label="First Name" :error="errors.first_name">
                        <el-input v-model="form.first_name" type="text"/>
                    </el-form-item>
                </el-col>
                <el-col :md="8">
                    <el-form-item label="Last Name" :error="errors.last_name">
                        <el-input v-model="form.last_name" type="text"/>
                    </el-form-item>
                </el-col>
                <el-col :md="8">
                    <el-form-item label="Patronymic" :error="errors.patronymic">
                        <el-input v-model="form.patronymic" type="text"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Organization" :error="errors.company_name">
                        <el-input v-model="form.company_name" type="text"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Phone no">
                        <el-input v-model="form.phone_number"
                                  type="text"
                                  v-maska
                                  data-maska="+### ## ###-##-##"
                                  placeholder="+998 93 123 45 56"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Passport number" :error="errors.passport_number">
                        <el-input v-model="form.passport_number"
                                  v-maska
                                  data-maska="@@#######"
                                  @maska="onMaska"
                                  type="text"/>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="Company" :error="errors.company_id">
                        <el-select v-model="form.company_id" class="w-100">
                            <el-option v-for="company in companies"
                                       :label="company.name"
                                       :value="company.id"
                            />
                        </el-select>
                    </el-form-item>
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
import {vMaska} from "maska"
import useNotification from "../../../../../../Admin/Resources/assets/hooks/useNotification";

const props = defineProps({
    model: Object,
    title: String,
    companies: Array
})
const errors = ref({})
const emit = defineEmits(['closeDialog'])
const form = ref({
    first_name: props.model?.first_name,
    last_name: props.model?.last_name,
    patronymic: props.model?.patronymic,
    organization: props.model?.organization,
    phone_number: props.model?.phone_number,
    passport_number: props?.model?.passport_number,
    company_id: props.model?.company_id
})
const {notify} = useNotification()

const onMaska = (event) => {
    form.passport_number = event.detail.masked.toUpperCase()
}

const handleSubmit = async () => {
    if (props.model) {
        axios.put(route('admin.visitor.guests.update', props.model), form.value)
            .then(res => {
                notify('success', res.data.message)
                emit('closeDialog')
            })
            .catch(e => {
                errors.value = e.response.data.errors
            });
        ;
    } else {
        await axios.post(route('admin.visitor.guests.store'), form.value)
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

<style scoped>

</style>
