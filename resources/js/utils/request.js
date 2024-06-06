import { getToken } from "./auth";
import router from "../router";
import store from "../store";

import axios from "axios";
// import store from "../store";
// Create axios instance
const service = axios.create({
  baseURL: "/api/v1/",
  timeout: 10000 // Request timeout
});

// service.defaults.withCredentials = true
// Request intercepter
service.interceptors.request.use(
  config => {
    if (getToken()) {
      config.headers["Authorization"] = "Bearer " + getToken(); // Set Token
    }
    return config;
  },
  error => {
    // store.dispatch('global/SHOW_PROGRESS', { loading: false })
    // Do something with request error
    console.log("error-axios", error); // for debug
    Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    return response.data;
  },
  error => {
    // remove auth user informations from cookies and return to login page
    console.log(error?.response);
    if (error?.response) {
      if ([401].includes(error?.response?.status)) {
        store.commit('auth/REMOVE_AUTH_DETAILS')
        router.push({ name: "login" });
      }

      if ([403].includes(error?.response?.status)) {
        router.push({ name: "403" });
      }
    }

    return Promise.reject(error);
  }
);

export default service;
