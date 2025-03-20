import {ref} from 'vue';

export default function usePagination() {
    const currentPage = ref(1)

    const indexMethod = (index, model) => {
        return index + firstItem(model)
    }

    function firstItem(model) {
        return model.data.length > 0 ? (currentPage.value - 1) * model.per_page + 1 : null;
    }

    function changePage(page, callback) {
        currentPage.value = page
        callback()
    }

    return {currentPage, indexMethod, changePage}
}
