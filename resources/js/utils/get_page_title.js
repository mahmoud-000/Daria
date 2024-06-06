import i18n from '../i18n';

const { t, te } = i18n.global

const title = import.meta.env.VITE_APP_NAME;
export const getPageTitle = (key) => {
  const [moduleName, moduleAction] = key.split('.')
  
  const hasKey = te(`action.${moduleAction}`);
  let titleHandle = t(`links.${moduleName}`, 1)

  if (moduleAction === 'list') {
    titleHandle = t(`links.${moduleName}`, 2)
  }
  if (hasKey) {
    const pageName = t(`action.${moduleAction}`, {
      module: titleHandle
    });

    return `${pageName} - ${title}`;
  }

  return `${titleHandle} - ${title}`;
}


export const slugify = str => {
  if (typeof str === 'string') {
    return str
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_-]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }
}