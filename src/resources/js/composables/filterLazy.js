import { ref, computed } from 'vue';
import { useStore } from 'vuex';

export const useFilterLazy = (moduleName, actionName = 'fetchOptions', getterName = 'getOptions') => {
  const store = useStore();
  const getterOptions = computed(() => store.getters[`${moduleName}/${getterName}`]);
  const getterMeta = computed(() => store.getters[`${moduleName}/getMeta`]);
  const options = ref(getterOptions.value);
  
  const loading = ref(false);

  const fetchDataFormServer = async (needle, update, otherQuery) => {
    loading.value = true;

    await store.dispatch(`${moduleName}/${actionName}`, {
      search: needle,
      ...otherQuery
    });

    loading.value = false;
    update(() => {
      options.value = getterOptions.value;
    });
  }
  const handleFilter = async (val, update, columns, otherQuery) => {
    if (val === "") {
      update(() => {
        options.value = getterOptions.value;
      });
      return;
    }

    update(async () => {
      const needle = val.toLowerCase();
      options.value = getterOptions.value.filter(
        (v) => columns.every(column => v[column].toLowerCase().indexOf(needle) > -1)
      );

      if (getterMeta.value && Object.keys(getterMeta.value).length === 0) {
        await fetchDataFormServer(needle, update, otherQuery)
      } else {
        if (!options.value.length || getterMeta.value.current_page !== getterMeta.value.last_page) {
          await fetchDataFormServer(needle, update, otherQuery)
        }
      }
    });
  };

  return {
    options,
    loading,
    handleFilter
  }
};
