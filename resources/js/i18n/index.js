import { createI18n } from 'vue-i18n';
import locales from '../locales';
import { Cookies } from 'quasar';

const i18n = createI18n({
  legacy: false,
  locale: Cookies.get('locale') ?? 'en',
  fallbackLocale: 'en',
  messages: locales,
});

export default i18n