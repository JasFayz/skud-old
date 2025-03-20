<template>
    <el-card>

        <el-descriptions
            title="Guest Info"
            :column="4"
            size="large"
            direction="vertical"
            border>
            <el-descriptions-item label="Photo" v-if="guest.photo" align="center">
                <el-image :src=" '/' +guest.photo" style="width: 150px; height: 150px"/>
            </el-descriptions-item>
            <el-descriptions-item label="Full name">{{ guest.full_name }}</el-descriptions-item>
            <el-descriptions-item label="Passport Number">{{ guest.passport_number }}</el-descriptions-item>
            <el-descriptions-item label="Organization">{{ guest.company_name }}</el-descriptions-item>
            <el-descriptions-item label="Phone">{{ guest.phone_number }}</el-descriptions-item>
            <el-descriptions-item label="Company">{{ guest.company?.name }}</el-descriptions-item>
            <el-descriptions-item label="Creator">{{ guest.creator?.name }}</el-descriptions-item>
            <el-descriptions-item label="Invite Count">
                {{ guest.invites.length }}
            </el-descriptions-item>
            <el-descriptions-item label="Has active invite" align="center">
                <el-icon v-if="guest.hasCurrentInvite" color="#67C23A">
                    <SuccessFilled/>
                </el-icon>
                <el-icon v-else color="#F56C6C">
                    <CircleCloseFilled/>
                </el-icon>
            </el-descriptions-item>
            <el-descriptions-item>

            </el-descriptions-item>

        </el-descriptions>
        <el-descriptions
            class="mt-3"
            title="Invite List"
            size="large"
            direction="vertical"
            border>
            <el-descriptions-item label="Invites" span="4">
                <el-table :data="guest.invites" size="small">
                    <el-table-column type="expand">
                        <template #default="props">
                            <el-descriptions
                                size="large"
                                direction="vertical"
                                border
                                column="2">
                                <el-descriptions-item label="Selected">
                                    <el-tag type="info"
                                            v-if="props.row.terminals?.length > 0"
                                            v-for="terminal in props.row?.terminals">
                                        <div class="d-flex justify-content-between">
                                            <el-icon color="#67C23A"
                                                     size="20"
                                                     v-if="props.row.attached_terminals.map( t => t.id)?.includes(terminal.id)">
                                                <CircleCheck/>
                                            </el-icon>
                                            <span class="mx-1">{{ terminal.name }}</span>
                                        </div>
                                    </el-tag>
                                    <el-empty v-else :image-size="100"/>
                                </el-descriptions-item>
                                <el-descriptions-item label="Attached">
                                    <el-tag type="success"
                                            v-if="props.row.attached_terminals?.length > 0"
                                            v-for="terminal in props.row?.attached_terminals">
                                        {{ terminal.name }}
                                    </el-tag>
                                    <el-empty v-else :image-size="100"/>
                                </el-descriptions-item>
                            </el-descriptions>
                        </template>
                    </el-table-column>
                    <el-table-column label="ID" min-width="40" max-width="100">
                        <template #default="props">
                            <a @click="toInvite(props.row.id)" class="btn">
                                {{ props.row.id }}
                            </a>
                        </template>
                    </el-table-column>
                    <el-table-column prop="start" label="Start"/>
                    <el-table-column prop="end" label="End"/>
                    <el-table-column prop="target_user.name" label="Target User"/>
                    <el-table-column prop="responsible_user.name" label="Responsible User"/>
                    <el-table-column label="Terminals count">
                        <template #default="props">
                            <span>{{ props.row.terminals?.length }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Attached terminals">
                        <template #default="props">
                            <span>{{ props.row.attached_terminals?.length }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column>
                        <template #default="props">
                            <el-tag size="small" type="success" v-if="props.row.status === 1">Подтвержден</el-tag>
                            <el-tag size="small" type="info" v-else-if="props.row.status === 2">Отклонен</el-tag>
                            <el-tag size="small" type="danger" v-else-if="props.row.status === 3">
                                <el-icon>
                                    <WarningFilled/>
                                </el-icon>
                                <span>Истек срок</span>
                            </el-tag>
                            <el-tag size="small" v-else-if="props.row.status === 4">Завершен</el-tag>
                            <el-tag size="small" type="warning" v-else>В ожидании...</el-tag>
                        </template>
                    </el-table-column>
                </el-table>
            </el-descriptions-item>
        </el-descriptions>
    </el-card>
</template>

<script setup>
import {SuccessFilled, CircleCloseFilled} from '@element-plus/icons-vue';
import {WarningFilled, CircleCheck} from '@element-plus/icons-vue';

const props = defineProps({
    guest: Object
})

const toInvite = (id) => {
    location.replace(route('admin.visitor.invites.show', id))
}
</script>

<style scoped>

</style>
