export const getUnits = (state) => state.units
export const getUnit = (state) => state.unit
export const getPagination = (state) => state.pagination
export const getOptions = (state) => state.options
export const getBaseUnits = (state) => state.options.filter(unit => unit.unit_id === null)
export const getUnitWithChilds = (state) => (baseId) => {
    if (baseId) {
        return state.options.filter(unit => unit.unit_id === baseId || unit.id === baseId)
    }
}
