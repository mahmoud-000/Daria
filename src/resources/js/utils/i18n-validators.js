// @/utils/i18n-validators.js
import * as validators from '@vuelidate/validators'
import i18n from "../i18n"

// or import { createI18nMessage } from '@vuelidate/validators'
const { createI18nMessage } = validators

// Create your i18n message instance. Used for vue-i18n@9
const withI18nMessage = createI18nMessage({ t: i18n.global.t.bind(i18n) })

// for vue-i18n@8
// const withI18nMessage = createI18nMessage({ t: i18n.t.bind(i18n) })

// wrap each validator.
export const required = withI18nMessage(validators.required, { messagePath: () => 'validations.required' })
export const integer = withI18nMessage(validators.integer, { messagePath: () => 'validations.integer' })
export const alpha = withI18nMessage(validators.alpha, { messagePath: () => 'validations.alpha' })
export const alphaNum = withI18nMessage(validators.alphaNum, { messagePath: () => 'validations.alphaNum' })
export const url = withI18nMessage(validators.url, { messagePath: () => 'validations.url' })
export const email = withI18nMessage(validators.email, { messagePath: () => 'validations.email' })
// validators that expect a parameter should have `{ withArguments: true }` passed as a second parameter, to annotate they should be wrapped
export const sameAs = withI18nMessage(validators.sameAs, { withArguments: true, messagePath: () => 'validations.sameAs' })
export const minLength = withI18nMessage(validators.minLength, { withArguments: true, messagePath: () => 'validations.minLength' })
export const maxLength = withI18nMessage(validators.maxLength, { withArguments: true, messagePath: () => 'validations.maxLength' })
export const requiredIf = withI18nMessage(validators.requiredIf, { withArguments: true, messagePath: () => 'validations.requiredIf' })
export const requiredUnless = withI18nMessage(validators.requiredUnless, { withArguments: true, messagePath: () => 'validations.requiredUnless' })
export const minValue = withI18nMessage(validators.minValue, { withArguments: true, messagePath: () => 'validations.minValue' })
export const maxValue = withI18nMessage(validators.maxValue, { withArguments: true, messagePath: () => 'validations.maxValue' })
// or you can provide the param at definition, statically
