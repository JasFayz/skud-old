<template>
    <el-card>
        <el-descriptions title="Invite Info"
                         :column="4"
                         size="large"
                         direction="vertical"
                         border>
            <el-descriptions-item label="QR" align="center">
                <div class="d-flex justify-content-center">
                    <a :href="'/admin/visitor/invite/'+ invite.id+'/download-qr-code'" class="mr-2">
                        <span v-html="invite.qr_code"></span>
                    </a>
                    <image-view :src="invite.guest.photo ? '/'+ invite.guest.photo : null"/>
                </div>
            </el-descriptions-item>
            <el-descriptions-item label="Guest Name">{{ invite.guest.name }}</el-descriptions-item>
            <el-descriptions-item label="Start">{{ invite.start }}</el-descriptions-item>
            <el-descriptions-item label="End"> {{ invite.end }}</el-descriptions-item>
            <el-descriptions-item label="Responsible Person">{{
                    invite.responsible_user.name
                }}
            </el-descriptions-item>
            <el-descriptions-item label="Target Person">{{ invite.target_user.name }}</el-descriptions-item>
            <el-descriptions-item label="Status">
                <el-button class="btn p-0" @click="() => approveInviteDialog = true">
                    <el-tag size="small" type="success" v-if="invite.status === 1">Подтвержден</el-tag>
                    <el-tag size="small" type="info" v-else-if="invite.status === 2">Отклонен</el-tag>
                    <el-tag size="small" type="danger" v-else-if="invite.status === 3">
                        <el-icon>
                            <WarningFilled/>
                        </el-icon>
                        <span>Истек срок</span>
                    </el-tag>
                    <el-tag size="small" v-else-if="invite.status === 4">Завершен</el-tag>
                    <el-tag size="small" type="warning" v-else>В ожидании...</el-tag>
                </el-button>
            </el-descriptions-item>
            <el-descriptions-item></el-descriptions-item>
            <el-descriptions-item label="Selected Terminals" span="2">
                <el-tag type="info"
                        v-if="invite.terminals?.length > 0"
                        v-for="terminal in invite.terminals"> {{ terminal.name }}
                </el-tag>
                <el-empty v-else :image-size="50"/>
            </el-descriptions-item>
            <el-descriptions-item label="Attaching Terminals" span="2">
                <el-tag type="success"
                        v-if="invite.attached_terminals?.length"
                        v-for="terminal in invite.attached_terminals">{{ terminal.name }}
                </el-tag>
                <el-empty v-else :image-size="50"/>
            </el-descriptions-item>
        </el-descriptions>
    </el-card>
    <set-invite-status-dialog v-model="approveInviteDialog"
                              @inviteStatusChanged="inviteStatusChanged"
                              @closeDialog="approveInviteDialog = false"
                              :invite="invite"/>
</template>

<script setup>
import {ref} from 'vue';

import {WarningFilled} from "@element-plus/icons-vue";
import ImageView from "../../../components/ImageView.vue";
import SetInviteStatusDialog from '../../../components/Invite/SetInviteStatusDialog.vue';

const props = defineProps({
    invite: Object
})

const approveInviteDialog = ref(false)
const inviteStatusChanged = () => {
    location.reload()
}


</script>

<style scoped>

</style>
