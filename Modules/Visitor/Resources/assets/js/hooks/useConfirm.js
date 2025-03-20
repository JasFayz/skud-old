import {ElMessage, ElMessageBox} from "element-plus";
import useNotification from "../../../../../Admin/Resources/assets/hooks/useNotification";
import {ref} from 'vue';

export default function useConfirm() {
    const {notify} = useNotification()
    const operationStatus = ref(false)
    const message = ref('Proxy will permanently delete the file. Continue?')
    const title = ref('Warning')
    const options = ref({
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
    })

    function confirmDelete(route, method) {
        ElMessageBox.confirm(
            message.value,
            title.value,
            options.value
        ).then(() => {
            axios({
                method: method,
                url: route
            }).then(res => {
                notify('success', 'Success', res.data.message)
                operationStatus.value = true
            }).catch(e => {
                notify('error', 'Error', e.response.message)
                operationStatus.value = false
            })
        }).catch(() => {
            ElMessage({
                type: 'info',
                message: 'Delete canceled',
            })
        })
    }

    return {
        confirmDelete, operationStatus, message,
        title,
        options
    };
}
