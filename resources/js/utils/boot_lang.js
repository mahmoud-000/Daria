import { Cookies, Quasar } from "quasar"

export const langList = import.meta.glob('../../../node_modules/quasar/lang/(ar|en-US).mjs')

export const bootLangFun = async () => {
  const langIso = [null, '', 'en'].includes(Cookies.get('locale')) ? "en-US" : Cookies.get('locale') // ... some logic to determine it (use Cookies Plugin?)

  try {
    langList[`../../../node_modules/quasar/lang/${langIso}.mjs`]().then(lang => {
      Quasar.lang.set(lang.default)
    })
  }
  catch (err) {
    console.log('err', err);
    // Requested Quasar Language Pack does not exist,
    // let's not break the app, so catching error
  }
}
export const bootLang = bootLangFun()