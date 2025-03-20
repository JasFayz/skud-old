<template>
    <el-card>
        <template #header>
            <div class="d-flex justify-content-between align-items-center ">
                <h5>Гости</h5>
                <div>
                    <el-checkbox v-model="filter.show_trashed"
                                 label="Trashed"
                                 @change="handleSearch" class="mr-2"/>

                    <el-button type="primary" @click="() => {
                        guestModal = true
                    }">Create
                    </el-button>

                </div>
            </div>
        </template>

        <el-table :data="guests.data" class="mb-2">
            <el-table-column type="index"/>
            <el-table-column>
                <template #default="props">
                    <image-view :src=" props.row?.photo ? '/' + props.row?.photo : null"/>
                </template>
            </el-table-column>
            <el-table-column prop="first_name" label="First Name">
                <template #header>
                    <el-input v-model="filter.full_name" placeholder="Full Name"
                              @keydown.enter="handleSearch"/>
                </template>
                <template #default="props">
                    <el-icon v-if="props.row.deleted_at" color="#E6A23C">
                        <WarningFilled/>
                    </el-icon>
                    <span>{{ props.row.full_name }}</span>
                </template>
            </el-table-column>
            <el-table-column prop="passport_number" label="Passport Number"
                             @keydown.enter="handleSearch">
                <template #header>
                    <el-input
                        v-model="filter.passport_number" placeholder="Passport number"
                        @keydown.enter="handleSearch"/>
                </template>

            </el-table-column>
            <el-table-column prop="phone_number" label="Phone"/>
            <el-table-column prop="company_name" label="Organization">
                <template #header>
                    <el-input v-model="filter.company_name" placeholder="Organization"
                              @keydown.enter="handleSearch"
                    />
                </template>
            </el-table-column>
            <el-table-column prop="creator.name" label="Creator"></el-table-column>
            <el-table-column prop="company.name" label="Company">
                <template #header="props">
                    <el-select v-model="filter.company_id" @change="handleSearch">
                        <el-option v-for="company in companies"
                                   :id="company.id"
                                   :value="company.id.toString()"
                                   :label="company.name"/>
                    </el-select>
                </template>
            </el-table-column>
            <el-table-column fixed="right" label="Operations" align="right">
                <template #default="props">
                    <el-dropdown trigger="click">
                        <el-button size="small">
                            More
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click="() => {
                                    inviteModal= true
                                    guest = props.row
                                }">
                                    Создать приглашение
                                </el-dropdown-item>
                                <el-dropdown-item @click="handleGuestInfo(props.row.id)">
                                    Инфо
                                </el-dropdown-item>
                                <el-dropdown-item @click="() => {
                                    guest=props.row
                                    guestModal = true }">
                                    Редактировать
                                </el-dropdown-item>
                                <el-dropdown-item class="bg-danger"
                                                  @click="handleRemoveGuestPhoto(props.row.id)">
                                    Удалить фото
                                </el-dropdown-item>
                                <el-dropdown-item class="bg-danger" @click="handleRemoveGuest(props.row.id)">
                                    Удалить гостя
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination
            v-model:current-page="guests.current_page"
            :hide-on-single-page="true"
            :total="guests.total"
            :page-size="guests.per_page"
            @current-change="(currentPage) => {
                 filter.page = currentPage
                handleSearch()
            }"
            layout="prev, pager, next, jumper, total"
        />
    </el-card>
    <guest-modal @closeDialog="() => {
        guestModal=false
        reloadPage()
    }" v-model="guestModal" v-if="guestModal" :companies="companies" :model="guest"/>

    <invite-modal v-model="inviteModal" v-if="inviteModal"
                  :companies="companies"
                  :users="users" :zones="zones"
                  :guest="guest"
                  @closeDialog="() => { inviteModal=false}"/>
</template>
<script setup>
import {ref, watch} from 'vue';
import {vMaska} from "maska"
import {WarningFilled} from '@element-plus/icons-vue'
import GuestModal from "../../../components/Guest/GuestModal.vue";
import useConfirm from "../../../hooks/useConfirm";
import InviteModal from "../../../components/Invite/InviteModal.vue";
import ImageView from "../../../components/ImageView.vue";

const {operationStatus, message, confirmDelete} = useConfirm()
const props = defineProps({
    guests: Object,
    filters: Object,
    companies: Array,
    users: Array,
    zones: Array
})
const currentPage = ref(parseInt(props.filters?.page) ?? 1)
const guestModal = ref(false)
const inviteModal = ref(false)
const guest = ref()
const reloadPage = (query) => {
    location.replace(route('admin.visitor.guests.index', query))
}
watch(() => operationStatus.value, (newValue) => {
    if (newValue) {
        reloadPage({...props.filters})
    }
})

const filter = ref({
    passport_number: props.filters?.passport_number,
    full_name: props.filters?.full_name,
    company_id: props.filters?.company_id,
    show_trashed: props.filters?.show_trashed === '1',
    filter: props.filters?.company_name
})

const handleSearch = () => {
    reloadPage({...filter.value})
}
const handleRemoveGuestPhoto = (guestId) => {
    message.value = 'Do you want to delete remove guest photo';
    confirmDelete(route('admin.visitor.guests.remove-photo', guestId), 'patch')
}
const handleRemoveGuest = (guestId) => {
    message.value = 'Do you want to delete remove guest';
    confirmDelete(route('admin.visitor.guests.destroy', guestId), 'delete')
}
const handleGuestInfo = (guestId) => {
    location.replace(route('admin.visitor.guests.show', guestId))
}

</script>

