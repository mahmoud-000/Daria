// vite.config.js
import { defineConfig } from "file:///C:/laragon/www/daria-11/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/laragon/www/daria-11/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///C:/laragon/www/daria-11/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { quasar, transformAssetUrls } from "file:///C:/laragon/www/daria-11/node_modules/@quasar/vite-plugin/src/index.js";
var vite_config_default = defineConfig({
  build: {
    chunkSizeWarningLimit: 1e3
  },
  // Commented In Itemion Build
  server: {
    hmr: {
      protocol: "ws",
      host: "localhost"
    }
  },
  plugins: [
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    quasar({
      sassVariables: "resources/sass/quasar-variables.sass"
    }),
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    })
  ],
  test: {
    testTimeout: 3e4,
    environment: "happy-dom",
    name: "unit",
    globals: true
  }
  // resolve: {
  //     alias: {
  //         vue: 'vue/dist/vue.esm-bundler.js',
  //     },
  // },
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxsYXJhZ29uXFxcXHd3d1xcXFxkYXJpYS0xMVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcbGFyYWdvblxcXFx3d3dcXFxcZGFyaWEtMTFcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L2xhcmFnb24vd3d3L2RhcmlhLTExL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7IHF1YXNhciwgdHJhbnNmb3JtQXNzZXRVcmxzIH0gZnJvbSAnQHF1YXNhci92aXRlLXBsdWdpbidcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBidWlsZDoge1xuICAgICAgICBjaHVua1NpemVXYXJuaW5nTGltaXQ6IDEwMDBcbiAgICB9LFxuICAgIC8vIENvbW1lbnRlZCBJbiBQcm9kdWN0aW9uIEJ1aWxkXG4gICAgc2VydmVyOiB7XG4gICAgICAgIGhtcjoge1xuICAgICAgICAgICAgcHJvdG9jb2w6ICd3cycsXG4gICAgICAgICAgICBob3N0OiAnbG9jYWxob3N0J1xuICAgICAgICB9XG4gICAgfSxcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIHZ1ZSh7XG4gICAgICAgICAgICB0ZW1wbGF0ZToge1xuICAgICAgICAgICAgICAgIHRyYW5zZm9ybUFzc2V0VXJsczoge1xuICAgICAgICAgICAgICAgICAgICBiYXNlOiBudWxsLFxuICAgICAgICAgICAgICAgICAgICBpbmNsdWRlQWJzb2x1dGU6IGZhbHNlLFxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB9LFxuICAgICAgICB9KSxcbiAgICAgICAgcXVhc2FyKHtcbiAgICAgICAgICAgIHNhc3NWYXJpYWJsZXM6ICdyZXNvdXJjZXMvc2Fzcy9xdWFzYXItdmFyaWFibGVzLnNhc3MnXG4gICAgICAgIH0pLFxuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9jc3MvYXBwLmNzcycsICdyZXNvdXJjZXMvanMvYXBwLmpzJ10sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICBdLFxuICAgIHRlc3Q6IHtcbiAgICAgICAgdGVzdFRpbWVvdXQ6IDMwXzAwMCxcbiAgICAgICAgZW52aXJvbm1lbnQ6ICdoYXBweS1kb20nLFxuICAgICAgICBuYW1lOiAndW5pdCcsXG4gICAgICAgIGdsb2JhbHM6IHRydWVcbiAgICB9LFxuICAgIC8vIHJlc29sdmU6IHtcbiAgICAvLyAgICAgYWxpYXM6IHtcbiAgICAvLyAgICAgICAgIHZ1ZTogJ3Z1ZS9kaXN0L3Z1ZS5lc20tYnVuZGxlci5qcycsXG4gICAgLy8gICAgIH0sXG4gICAgLy8gfSxcbn0pOyJdLAogICJtYXBwaW5ncyI6ICI7QUFBK1AsU0FBUyxvQkFBb0I7QUFDNVIsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sU0FBUztBQUNoQixTQUFTLFFBQVEsMEJBQTBCO0FBRTNDLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLE9BQU87QUFBQSxJQUNILHVCQUF1QjtBQUFBLEVBQzNCO0FBQUE7QUFBQSxFQUVBLFFBQVE7QUFBQSxJQUNKLEtBQUs7QUFBQSxNQUNELFVBQVU7QUFBQSxNQUNWLE1BQU07QUFBQSxJQUNWO0FBQUEsRUFDSjtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsSUFBSTtBQUFBLE1BQ0EsVUFBVTtBQUFBLFFBQ04sb0JBQW9CO0FBQUEsVUFDaEIsTUFBTTtBQUFBLFVBQ04saUJBQWlCO0FBQUEsUUFDckI7QUFBQSxNQUNKO0FBQUEsSUFDSixDQUFDO0FBQUEsSUFDRCxPQUFPO0FBQUEsTUFDSCxlQUFlO0FBQUEsSUFDbkIsQ0FBQztBQUFBLElBQ0QsUUFBUTtBQUFBLE1BQ0osT0FBTyxDQUFDLHlCQUF5QixxQkFBcUI7QUFBQSxNQUN0RCxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsRUFDTDtBQUFBLEVBQ0EsTUFBTTtBQUFBLElBQ0YsYUFBYTtBQUFBLElBQ2IsYUFBYTtBQUFBLElBQ2IsTUFBTTtBQUFBLElBQ04sU0FBUztBQUFBLEVBQ2I7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBTUosQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
