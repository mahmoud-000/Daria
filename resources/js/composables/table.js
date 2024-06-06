import { computed, ref } from 'vue';
import store from '../store'
import { useRoute } from 'vue-router';

export const useTable = (moduleName, actionName) => {
    const route = useRoute();
    const getPagination = computed(() => store.getters[`${moduleName}/getPagination`])
    
    const pagination = ref({
        page: route.query.page ?? getPagination.value.page,
        rowsPerPage: route.query.rowsPerPage ?? getPagination.value.rowsPerPage,
        sortBy: route.query.sortBy ?? getPagination.value.sortBy,
        descending: route.query.descending ?? getPagination.value.descending,
        rowsNumber: 0,
    });

    const handleRequest = async (tableOptions) => {
        const { page, rowsPerPage, sortBy, descending, rowsNumber } = tableOptions.pagination;
        const filter = tableOptions.filter;
        const payload = { filter, page, rowsPerPage, sortBy, descending }
        await store.dispatch(`${moduleName}/${actionName}`, payload)
    }

    return {
        handleRequest,
        pagination
    }
};
