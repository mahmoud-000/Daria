import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from "../../../utils/notify";
import {
  login,
  changePassword,
  forgetPassword,
  resetPassword,
  logout,
  profileUpdateUser,
  profileUpdateCustomer,
  permissions,
  profileUser,
  profileCustomer
} from "../../../models/Auth";
import router from "../../../router";
import { Loading } from "quasar";

export const loginAction = ({ commit }, userInfo) => {
  let urlParams = new URLSearchParams(window.location.search);
  fireLoadingSpinner('login_process')
  return new Promise((resolve, reject) => {
    login(userInfo)
      .then((response) => {
        if (response.success) {
          commit("SET_LOGIN_USER", response.payload.user);
          commit("SET_TOKEN", response.payload.token);
          commit("SET_PERMISSIONS", response.payload.permissions);
          commit('company/SET_CURRENCY', response.payload.currency, { root: true })
          fireSuccessNotify(response, response.payload?.message)
          router.push(
            urlParams.get("redirect") || { name: "dashboard" }
          );
        }

        resolve(response);
      })
      .catch((error) => {
        fireErrorNotify(error, error.response?.data?.payload.message)
        commit("REMOVE_AUTH_DETAILS");
        reject(error);
      });
  });
};

export const logoutAction = ({ commit }) => {
  fireLoadingSpinner('logout_process')
  return new Promise((resolve, reject) => {
    logout()
      .then((response) => {
        fireSuccessNotify(response, response.payload?.message)
        Loading.hide()
        commit("REMOVE_AUTH_DETAILS");
        router.push({ name: "login" });
        resolve(response);
      })
      .catch((error) => {
        fireErrorNotify(error, error.response?.data?.payload?.message)
        commit("REMOVE_AUTH_DETAILS");
        router.push({ name: "login" });
        reject(error);
      });
  });
};

export const changePasswordAction = ({ commit }, data) => {
  fireLoadingSpinner('logout_process')
  return new Promise((resolve, reject) => {
    changePassword(data)
      .then((response) => {
        fireSuccessNotify(response, response.payload.message)
        resolve(response);
      })
      .catch((error) => {
        fireErrorNotify(error, error.response?.data?.payload.message)
        reject(error);
      });
  });
};

export const forgetPasswordAction = ({ commit }, data) => {
  fireLoadingSpinner('logout_process')
  return new Promise((resolve, reject) => {
    forgetPassword(data)
      .then((response) => {
        fireSuccessNotify(response, response.payload.message)
        resolve(response);
      })
      .catch((error) => {
        fireErrorNotify(error, error.response?.data?.payload.message)
        reject(error);
      });
  });
};

export const resetPasswordAction = ({ commit }, data) => {
  fireLoadingSpinner('logout_process')
  return new Promise((resolve, reject) => {
    resetPassword(data)
      .then((response) => {
        fireSuccessNotify(response, response.payload.message)
        resolve(response);
      })
      .catch((error) => {
        fireErrorNotify(error, error.response?.data?.payload.message)
        reject(error);
      });
  });
};

export const fetchProfileUser = ({ commit }) => {
  return new Promise((resolve, reject) => {
    profileUser().then(res => {
      commit('SET_PROFILE', res.data)
      resolve(res);
    })
      .catch(error => {
        reject(error);
      });
  })
};

export const fetchProfileCustomer = ({ commit }) => {
  return new Promise((resolve, reject) => {
    profileCustomer().then(res => {
      commit('SET_PROFILE', res.data)
      resolve(res);
    })
      .catch(error => {
        reject(error);
      });
  })
};

export const updateProfileUser = ({ commit }, profile) => {
  fireLoadingSpinner('updating_module')
  return new Promise((resolve, reject) => {
    profileUpdateUser(profile).then(res => {
      fireSuccessNotify(res, res.payload.message)
      commit('SET_LOGIN_USER', res.payload.loginUser)
      resolve(res);
    })
      .catch(error => {
        fireErrorNotify(error, error.response?.data?.payload)
        reject(error);
      });
  })
};

export const updateProfileCustomer = ({ commit }, profile) => {
  fireLoadingSpinner('updating_module')
  return new Promise((resolve, reject) => {
    profileUpdateCustomer(profile).then(res => {
      fireSuccessNotify(res, res.payload.message)
      commit('SET_LOGIN_USER', res.payload.loginUser)
      resolve(res);
    })
      .catch(error => {
        fireErrorNotify(error, error.response?.data?.payload)
        reject(error);
      });
  })
};

export const fetchAuthPermissions = ({ commit }) => {
  return new Promise((resolve, reject) => {
    permissions().then(res => {
      commit("SET_PERMISSIONS", res);
      resolve(res);
    })
      .catch(error => {
        reject(error);
      });
  })
};