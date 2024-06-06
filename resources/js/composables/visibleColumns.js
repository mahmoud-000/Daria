import { ref } from 'vue';

export const useVisibleColumns = (columns) => {
  const visibleColumnsObject = columns.reduce((acc, cur) => {
    acc[cur.name] = cur.label;
    return acc;
  }, {});
  const visibleColumns = ref(Object.keys(visibleColumnsObject));
  return {
    visibleColumns
  }
};
