import validations from "./validations"
import table from "./table"
import links from "./links"
import card from "./card"
import messages from "./messages"
import auth from "./auth"
import inputs from "./inputs"
import select from "./select"
import action from "./action"
export default {
    locales: {
        'en': 'En',
        'ar': 'Ar',
    },
    ...validations,
    ...table,
    ...links,
    ...card,
    ...messages,
    ...auth,
    ...inputs,
    ...select,    
    ...action,    
}
