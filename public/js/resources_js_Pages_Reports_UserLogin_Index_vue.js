"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Reports_UserLogin_Index_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia */ "./node_modules/@inertiajs/inertia/dist/index.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      showNav: false,
      notifCount: this.$page.props.app.notifCount,
      alert: {
        type: null,
        message: null
      }
    };
  },
  created: function created() {
    var _this = this;

    _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_0__.Inertia.on('navigate', function () {
      _this.showNav = false;
    });
  },
  mounted: function mounted() {
    var _this2 = this;

    //  Manually trigger alert from Vue Component
    this.eventHub.$on('show-alert', function (alert) {
      _this2.alert.message = alert.message;
      _this2.alert.type = alert.type;
    }); //  Manually cancel alert that was triggered

    this.eventHub.$on('clear-alert', function () {
      _this2.alert.message = null;
      _this2.alert.type = null;
    }); //  Update the notification bell with unread message count

    this.eventHub.$on('update-unread', function (unread) {
      _this2.notifCount = unread;
    });
  },
  computed: {
    //  All application information
    app: function app() {
      return this.$page.props.app;
    },
    //  If the navbar is open or closed
    navbarActive: function navbarActive() {
      return this.showNav ? 'active' : '';
    },
    //  Dynamically built navbar
    navbar: function navbar() {
      return this.$page.props.navBar;
    },
    //  Dynamically built Breadcrumbs
    breadcrumbs: function breadcrumbs() {
      var crumbs = [];
      this.$page.props.breadcrumbs.forEach(function (item) {
        crumbs.push({
          text: item.title,
          href: item.url,
          active: item.is_current_page
        });
      });
      return crumbs;
    }
  },
  metaInfo: {
    title: 'Welcome',
    titleTemplate: '%s | Tech Bench'
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Layouts_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../Layouts/app */ "./resources/js/Layouts/app.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  layout: _Layouts_app__WEBPACK_IMPORTED_MODULE_0__["default"],
  props: {
    /**
     * Array of objects from /app/Models/User
     */
    user_list: {
      type: Array,
      required: true
    }
  },
  data: function data() {
    return {
      available_users: this.user_list,
      add_users: [],
      rem_users: [],
      submitted: false,
      form: this.$inertia.form({
        start_date: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000),
        stop_date: new Date(),
        selected_list: []
      })
    };
  },
  methods: {
    submitForm: function submitForm() {
      if (this.form.selected_list.length == 0) {
        this.$bvModal.msgBoxOk('You must add at least one User', {
          title: 'Error:',
          size: 'sm',
          buttonSize: 'sm',
          centered: true
        });
      } else {
        this.submitted = true;
        this.form.put(route('reports.user-login-report.update', 'details'));
      }
    },
    add_item: function add_item() {
      var form = this.form;
      var list = this.available_users;
      this.add_users.forEach(function (elem, index) {
        for (var i = 0; i < list.length; i++) {
          if (list[i].username === elem) {
            form.selected_list.push(list[i]);
            list.splice(i, 1);
          }
        }
      });
      this.add_users = [];
    },
    remove_item: function remove_item() {
      var form = this.form;
      var list = this.available_users;
      this.rem_users.forEach(function (elem, index) {
        for (var i = 0; i < form.selected_list.length; i++) {
          if (form.selected_list[i].username === elem) {
            list.push(form.selected_list[i]);
            form.selected_list.splice(i, 1);
          }
        }
      });
      this.rem_users = [];
    }
  }
});

/***/ }),

/***/ "./resources/js/Layouts/app.vue":
/*!**************************************!*\
  !*** ./resources/js/Layouts/app.vue ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./app.vue?vue&type=template&id=191620ed& */ "./resources/js/Layouts/app.vue?vue&type=template&id=191620ed&");
/* harmony import */ var _app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./app.vue?vue&type=script&lang=js& */ "./resources/js/Layouts/app.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__.render,
  _app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Layouts/app.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Reports/UserLogin/Index.vue":
/*!********************************************************!*\
  !*** ./resources/js/Pages/Reports/UserLogin/Index.vue ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Index_vue_vue_type_template_id_62b42b38___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=62b42b38& */ "./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=template&id=62b42b38&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_62b42b38___WEBPACK_IMPORTED_MODULE_0__.render,
  _Index_vue_vue_type_template_id_62b42b38___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Reports/UserLogin/Index.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Layouts/app.vue?vue&type=script&lang=js&":
/*!***************************************************************!*\
  !*** ./resources/js/Layouts/app.vue?vue&type=script&lang=js& ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./app.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Layouts/app.vue?vue&type=template&id=191620ed&":
/*!*********************************************************************!*\
  !*** ./resources/js/Layouts/app.vue?vue&type=template&id=191620ed& ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./app.vue?vue&type=template&id=191620ed& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=template&id=191620ed&");


/***/ }),

/***/ "./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=template&id=62b42b38&":
/*!***************************************************************************************!*\
  !*** ./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=template&id=62b42b38& ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_62b42b38___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_62b42b38___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_62b42b38___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Index.vue?vue&type=template&id=62b42b38& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=template&id=62b42b38&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=template&id=191620ed&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=template&id=191620ed& ***!
  \************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "app-layout" }, [
    _c("nav", { staticClass: "navbar top-navbar fixed-top" }, [
      _c(
        "div",
        { staticClass: "navbar-logo-wrapper d-flex" },
        [
          _c(
            "inertia-link",
            {
              staticClass: "navbar-logo",
              attrs: { href: _vm.route("dashboard") },
            },
            [
              _c("img", {
                staticClass: "mr-2",
                attrs: { src: _vm.app.logo, alt: _vm.app.name },
              }),
            ]
          ),
        ],
        1
      ),
      _vm._v(" "),
      _c("div", { staticClass: "navbar-brand d-none d-md-flex" }, [
        _c("h2", [_vm._v(_vm._s(_vm.app.name))]),
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "navbar-data" },
        [
          _c(
            "inertia-link",
            {
              directives: [
                {
                  name: "b-tooltip",
                  rawName: "v-b-tooltip.hover",
                  modifiers: { hover: true },
                },
              ],
              staticClass: "text-muted",
              attrs: {
                href: _vm.route("about"),
                title: "About " + _vm.app.name,
              },
            },
            [_c("i", { staticClass: "fas fa-info-circle" })]
          ),
          _vm._v(" "),
          _c(
            "inertia-link",
            {
              attrs: {
                as: "b-button",
                href: _vm.route("dashboard"),
                size: "sm",
                pill: "",
                variant: "info",
              },
            },
            [
              _c("i", { staticClass: "fas fa-bell" }),
              _vm._v(" "),
              _c("b-badge", { attrs: { pill: "", variant: "warning" } }, [
                _vm._v(_vm._s(_vm.notifCount)),
              ]),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-dropdown",
            {
              directives: [
                {
                  name: "b-tooltip",
                  rawName: "v-b-tooltip.hover",
                  modifiers: { hover: true },
                },
              ],
              attrs: { variant: "link", title: "Account" },
              scopedSlots: _vm._u([
                {
                  key: "button-content",
                  fn: function () {
                    return [
                      _c("b-avatar", {
                        attrs: {
                          variant: "warning",
                          text: _vm.app.user.initials,
                        },
                      }),
                    ]
                  },
                  proxy: true,
                },
              ]),
            },
            [
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  attrs: {
                    as: "b-dropdown-item",
                    href: _vm.route("settings.index"),
                  },
                },
                [_c("i", { staticClass: "fas fa-cog" }), _vm._v(" Settings")]
              ),
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  attrs: {
                    as: "b-dropdown-item",
                    href: _vm.route("password.index"),
                  },
                },
                [
                  _c("i", { staticClass: "fas fa-key" }),
                  _vm._v(" Change Password"),
                ]
              ),
              _vm._v(" "),
              _c("b-dropdown-divider"),
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  attrs: {
                    as: "b-dropdown-item",
                    method: "post",
                    href: _vm.route("logout"),
                  },
                },
                [
                  _c("i", { staticClass: "fas fa-sign-out-alt" }),
                  _vm._v(" Logout"),
                ]
              ),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "navbar-toggler d-xl-none",
              attrs: { type: "button" },
              on: {
                click: function ($event) {
                  _vm.showNav = !_vm.showNav
                },
              },
            },
            [_c("i", { staticClass: "fas fa-bars" })]
          ),
        ],
        1
      ),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "container-fluid page-body-wrapper" }, [
      _c(
        "nav",
        {
          staticClass: "sidebar sidebar-nav",
          class: _vm.navbarActive,
          attrs: { id: "side-nav" },
        },
        [
          _c(
            "ul",
            { staticClass: "nav" },
            _vm._l(_vm.navbar, function (l) {
              return _c(
                "li",
                { key: l.name, staticClass: "nav-item" },
                [
                  _c(
                    "inertia-link",
                    { staticClass: "nav-link", attrs: { href: l.route } },
                    [
                      _c("i", { staticClass: "menu-icon", class: l.icon }),
                      _vm._v(" "),
                      _c("span", { staticClass: "menu-title" }, [
                        _vm._v(_vm._s(l.name)),
                      ]),
                    ]
                  ),
                ],
                1
              )
            }),
            0
          ),
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "content" },
        [
          _c(
            "div",
            { staticClass: "content-wrapper" },
            [
              _vm.breadcrumbs.length
                ? _c("b-breadcrumb", { attrs: { items: _vm.breadcrumbs } })
                : _vm._e(),
              _vm._v(" "),
              _c(
                "b-alert",
                {
                  attrs: {
                    variant: _vm.$page.props.flash.type,
                    show: _vm.$page.props.flash.message ? 30 : false,
                  },
                },
                [
                  _c("p", { staticClass: "text-center" }, [
                    _vm._v(_vm._s(_vm.$page.props.flash.message)),
                  ]),
                ]
              ),
              _vm._v(" "),
              _c(
                "b-alert",
                {
                  attrs: {
                    variant: _vm.alert.type,
                    show: _vm.alert.message ? 30 : false,
                  },
                },
                [
                  _c("p", { staticClass: "text-center" }, [
                    _vm._v(_vm._s(_vm.alert.message)),
                  ]),
                ]
              ),
              _vm._v(" "),
              _vm._t("default"),
            ],
            2
          ),
          _vm._v(" "),
          _c("axios-error"),
          _vm._v(" "),
          _c("footer", { staticClass: " footer page-footer" }, [
            _c(
              "div",
              {
                staticClass:
                  "d-sm-flex justify-content-center justify-content-sm-between",
              },
              [
                _vm._m(0),
                _vm._v(" "),
                _c(
                  "span",
                  {
                    staticClass:
                      "text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center",
                  },
                  [_vm._v(_vm._s(_vm.app.version))]
                ),
              ]
            ),
          ]),
        ],
        1
      ),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "span",
      {
        staticClass:
          "text-muted text-center text-sm-left d-block d-sm-inline-block",
      },
      [
        _vm._v("Copyright © 2016-2022"),
        _c("span", { staticClass: "d-none d-md-inline" }, [
          _vm._v(" Butcherman - All rights reserved."),
        ]),
      ]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=template&id=62b42b38&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Reports/UserLogin/Index.vue?vue&type=template&id=62b42b38& ***!
  \******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _vm._m(0),
    _vm._v(" "),
    _c("div", { staticClass: "row justify-content-center" }, [
      _c("div", { staticClass: "col-md-8" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c("div", { staticClass: "card-title" }, [
                _vm._v("Select Options:"),
              ]),
              _vm._v(" "),
              _c("ValidationObserver", {
                scopedSlots: _vm._u([
                  {
                    key: "default",
                    fn: function (ref) {
                      var handleSubmit = ref.handleSubmit
                      return [
                        _c(
                          "b-overlay",
                          {
                            attrs: { show: _vm.submitted },
                            scopedSlots: _vm._u(
                              [
                                {
                                  key: "overlay",
                                  fn: function () {
                                    return [
                                      _c("form-loader", {
                                        attrs: { text: "Generating Report..." },
                                      }),
                                    ]
                                  },
                                  proxy: true,
                                },
                              ],
                              null,
                              true
                            ),
                          },
                          [
                            _vm._v(" "),
                            _c(
                              "b-form",
                              {
                                attrs: { novalidate: "" },
                                on: {
                                  submit: function ($event) {
                                    $event.preventDefault()
                                    return handleSubmit(_vm.submitForm)
                                  },
                                },
                              },
                              [
                                _c(
                                  "div",
                                  { staticClass: "row justify-content-center" },
                                  [
                                    _c(
                                      "div",
                                      { staticClass: "col-md-6 grid-margin" },
                                      [
                                        _c("date-picker", {
                                          attrs: {
                                            rules: "required",
                                            label: "Start Date",
                                          },
                                          model: {
                                            value: _vm.form.start_date,
                                            callback: function ($$v) {
                                              _vm.$set(
                                                _vm.form,
                                                "start_date",
                                                $$v
                                              )
                                            },
                                            expression: "form.start_date",
                                          },
                                        }),
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "div",
                                      { staticClass: "col-md-6" },
                                      [
                                        _c("date-picker", {
                                          attrs: {
                                            rules: "required",
                                            label: "Stop Date",
                                          },
                                          model: {
                                            value: _vm.form.stop_date,
                                            callback: function ($$v) {
                                              _vm.$set(
                                                _vm.form,
                                                "stop_date",
                                                $$v
                                              )
                                            },
                                            expression: "form.stop_date",
                                          },
                                        }),
                                      ],
                                      1
                                    ),
                                  ]
                                ),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  { staticClass: "row justify-content-center" },
                                  [
                                    _c(
                                      "div",
                                      { staticClass: "col-md-4 grid-margin" },
                                      [
                                        _c("label", [
                                          _vm._v("Available Users"),
                                        ]),
                                        _vm._v(" "),
                                        _c("b-form-select", {
                                          attrs: {
                                            multiple: "",
                                            options: _vm.available_users,
                                            "select-size": 10,
                                            "value-field": "username",
                                            "text-field": "full_name",
                                          },
                                          model: {
                                            value: _vm.add_users,
                                            callback: function ($$v) {
                                              _vm.add_users = $$v
                                            },
                                            expression: "add_users",
                                          },
                                        }),
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "div",
                                      {
                                        staticClass:
                                          "col-md-1 d-md-flex align-items-center",
                                      },
                                      [
                                        _c(
                                          "div",
                                          { staticClass: "text-center" },
                                          [
                                            _c(
                                              "b-button",
                                              {
                                                attrs: {
                                                  variant: "info",
                                                  block: "",
                                                },
                                                on: { click: _vm.add_item },
                                              },
                                              [
                                                _c("i", {
                                                  staticClass:
                                                    "fas fa-angle-double-right",
                                                }),
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-button",
                                              {
                                                attrs: {
                                                  variant: "info",
                                                  block: "",
                                                },
                                                on: { click: _vm.remove_item },
                                              },
                                              [
                                                _c("i", {
                                                  staticClass:
                                                    "fas fa-angle-double-left",
                                                }),
                                              ]
                                            ),
                                          ],
                                          1
                                        ),
                                      ]
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "div",
                                      { staticClass: "col-md-4 grid-margin" },
                                      [
                                        _c("label", [_vm._v("Selected Users")]),
                                        _vm._v(" "),
                                        _c("b-form-select", {
                                          attrs: {
                                            multiple: "",
                                            options: _vm.form.selected_list,
                                            "select-size": 10,
                                            "value-field": "username",
                                            "text-field": "full_name",
                                          },
                                          model: {
                                            value: _vm.rem_users,
                                            callback: function ($$v) {
                                              _vm.rem_users = $$v
                                            },
                                            expression: "rem_users",
                                          },
                                        }),
                                      ],
                                      1
                                    ),
                                  ]
                                ),
                                _vm._v(" "),
                                _c("submit-button", {
                                  staticClass: "mt-3",
                                  attrs: {
                                    button_text: "Run Report",
                                    submitted: _vm.submitted,
                                  },
                                }),
                              ],
                              1
                            ),
                          ],
                          1
                        ),
                      ]
                    },
                  },
                ]),
              }),
            ],
            1
          ),
        ]),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-12 grid-margin" }, [
        _c("h4", { staticClass: "text-center text-md-left" }, [
          _vm._v("User Login Report"),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);