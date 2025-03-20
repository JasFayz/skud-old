<template>
    <el-card>
        <template #header>
            <h5>Приглашения</h5>
        </template>

        <el-table :data="invites.data">
            <el-table-column type="index" align="left" label="#"/>
            <el-table-column label="QR">
                <template #default="props">
                    <a :href="'/admin/visitor/invite/'+ props.row.id+'/download-qr-code'" class="mr-2">
                        <span v-html="props.row.qr_code"></span>
                    </a>
                </template>
            </el-table-column>
            <el-table-column prop="guest.full_name" label="Guest Full Name">
                <template #header>
                    <el-input v-model="filter.guest_name"
                              placeholder="Guest Name"
                              @keydown.enter="handleSearch"
                    />
                </template>
            </el-table-column>
            <el-table-column prop="guest.passport_number" label="Passport Number">
                <template #header>
                    <el-input v-model="filter.passport_number"
                              placeholder="Passport Number"
                              @keydown.enter="handleSearch"/>
                </template>
            </el-table-column>
            <el-table-column prop="start" label="Start"/>
            <el-table-column prop="end" label="End"/>
            <el-table-column prop="status" label="Status"/>
            <el-table-column>
                <template #default="props">
                    <el-dropdown>
                        <el-button type="primary" size="small">
                            {{ props.row.terminals.length }}

                        </el-button>
                        <template #dropdown>
                            <el-tag class="m-1"
                                    v-for="terminal in props.row.terminals"
                                    @change="handleSearch">
                                {{ terminal.name }}
                            </el-tag>
                        </template>
                    </el-dropdown>

                </template>
            </el-table-column>
            <el-table-column label="Company" prop="company.name">
                <template #header>
                    <el-select v-model="filter.company_id" @change="handleSearch">
                        <el-option v-for="company in companies"
                                   :label="company.name"
                                   :value="company.id.toString()"
                        />
                    </el-select>
                </template>
            </el-table-column>
            <el-table-column label="Operation" align="right">
                <template #default="props">
                    <el-dropdown trigger="click">
                        <el-button size="small">
                            More
                        </el-button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item @click="handleInviteInfo(props.row.id)">
                                    Инфо
                                </el-dropdown-item>
                                <el-dropdown-item @click="() => {
                                    inviteModal = true
                                    invite = props.row
                                }">
                                    Редактировать
                                </el-dropdown-item>

                                <el-dropdown-item class="bg-danger" @click="handleDelete(props.row.id)">
                                    Delete
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination
            v-model:current-page="invites.current_page"
            :hide-on-single-page="true"
            :total="invites.total"
            :page-size="invites.per_page"
            @current-change="(currentPage ) => {
               filter.page = currentPage
               handleSearch()
            }"
            layout="prev, pager, next, jumper, total"
        />
    </el-card>

    <invite-modal v-model="inviteModal" v-if="inviteModal"
                  :users="users"
                  :zones="zones"
                  :model="invite"
                  :guest="invite.guest"
                  :companies="companies"
                  @closeDialog="() => {
                      inviteModal=false
                      reloadPage({...filter})
                  }"/>
</template>

<script setup>
import {ref, watch} from 'vue';
import InviteModal from "../../../components/Invite/InviteModal.vue";
import useConfirm from "../../../hooks/useConfirm";

const {confirmDelete, operationStatus, message} = useConfirm();
const props = defineProps({
    invites: Object,
    users: Array,
    zones: Array,
    filters: Object,
    companies: Array
})
const currentPage = ref(parseInt(props.filters?.page) ?? 1)
const filter = ref({
    passport_number: props.filters?.passport_number,
    guest_name: props.filters?.guest_name,
    company_id: props.filters?.company_id
})

const reloadPage = (query) => {
    location.replace(route('admin.visitor.invites.index', query))
}
watch(() => operationStatus.value, (newValue) => {
    if (newValue) {
        reloadPage({...props.filters})
    }
})

const invite = ref({})
const inviteModal = ref(false)
const handleInviteInfo = (inviteId) => {
    location.replace(route('admin.visitor.invites.show', inviteId))
}

const handleDelete = (inviteId) => {
    message.value = 'Вы точно хотите удалть инвайт?'
    confirmDelete(route('admin.visitor.invites.destroy', inviteId), 'delete')
}

const handleSearch = () => {
    reloadPage({...filter.value})
}
</script>


