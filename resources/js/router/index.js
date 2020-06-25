import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

const router = new Router({
  scrollBehavior() {
    return window.scrollTo({ top: 0, behavior: "smooth" });
  },
  routes: [
    // Dashboards

    {
      path: "/",
      name: "analytics",
      component: () => import("../PageComponents/Nations/Index.vue"),
    },
    // Pages

    {
      path: "/login",
      name: "login",
      meta: { layout: "userpages" },
      component: () => import("../PageComponents/Login.vue"),
    },
    {
      path: "/nations",
      name: "nations",
      component: () => import("../PageComponents/Nations/Index.vue"),
    },
    {
      path: "/nations/create",
      name: "create-nation",
      component: () => import("../PageComponents/Nations/Create.vue"),
    },
    {
      path: "/nations/edit/:id",
      props: true,
      name: "edit-nation",
      component: () => import("../PageComponents/Nations/Edit.vue"),
    },
    {
      path: "/logs",
      name: "logs",
      component: () => import("../PageComponents/Logs/Index.vue"),
    },
    {
      path: "/users",
      name: "users",
      component: () => import("../PageComponents/Users/Index.vue"),
    },
    {
      path: "/users/edit/:id",
      props: true,
      name: "edit-user",
      component: () => import("../PageComponents/Users/Edit.vue"),
    },
    {
      path: "/users/create",
      name: "create-user",
      component: () => import("../PageComponents/Users/Create.vue"),
    },
    {
      path: "/password/edit/:id",
      props: true,
      name: "change-password",
      component: () => import("../PageComponents/Users/ChangePassword.vue"),
    },
    {
      path: "/change/logo",
      props: true,
      name: "change-logo",
      component: () => import("../PageComponents/Users/ChangeLogo.vue"),
    }
  ],
});

export default router;
