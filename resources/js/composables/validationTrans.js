import { computed } from 'vue';
import { useStore } from 'vuex';

export const useValidationTrans = (field, rules) => {
  const store = useStore();
  const locales = computed(() => store.getters["locale/getLocales"]);
  const handleValidationTrans = (field, rules) =>
    locales.value.reduce(
      (a, locale) => ({
        ...a,
        [`${field}_${locale}`]: rules,
      }),
      {}
    );

  return {
    locales,
    handleValidationTrans
  }
};
