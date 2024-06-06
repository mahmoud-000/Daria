import { exportFile } from "quasar";

const wrapCsvValue = (val, formatFn, row) => {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`;
};

export const useExportTableCsv = (module, columns, rows, visibleColumns) => {
  const exportTableCsv = () => {
    let columnsFiltered = (columns.value).filter(column => visibleColumns.value.includes(column.name) && column.name !== 'actions');
    // naive encoding to csv format
    const content = [columnsFiltered.map((col) => wrapCsvValue(col.label))]
      .concat(
        rows.value.map((row) =>
        columnsFiltered
            .map((col) =>
              wrapCsvValue(
                typeof col.field === "function"
                  ? col.field(row)
                  : row[
                  col.field === void 0
                    ? col.name
                    : col.field
                  ],
                col.format,
                row
              )
            )
            .join(",")
        )
      )
      .join("\r\n");

    const status = exportFile(
      `${module}-export.csv`,
      content,
      "text/csv"
    );

    if (!status) {
      $q.notify({
        message: t("table.browser_denied_download"),
        color: "negative",
        icon: "warning",
      });
    }
  }
  return {
    exportTableCsv
  }
};
