"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_User_ChangePassword_vue"],{

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/User/ChangePassword.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/User/ChangePassword.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Layouts_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../Layouts/app */ "./resources/js/Layouts/app.vue");
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
  data: function data() {
    return {
      form: {
        current_password: '',
        password: '',
        password_confirmation: ''
      },
      submitted: false
    };
  },
  methods: {
    submitForm: function submitForm() {
      var _this = this;

      this.submitted = true;
      this.$inertia.post(route('password.store'), this.form, {
        onFinish: function onFinish() {
          _this.submitted = false;
        }
      });
    }
  },
  metaInfo: {
    title: 'Change Password'
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

/***/ "./resources/js/Pages/User/ChangePassword.vue":
/*!****************************************************!*\
  !*** ./resources/js/Pages/User/ChangePassword.vue ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ChangePassword_vue_vue_type_template_id_325f6094___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ChangePassword.vue?vue&type=template&id=325f6094& */ "./resources/js/Pages/User/ChangePassword.vue?vue&type=template&id=325f6094&");
/* harmony import */ var _ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ChangePassword.vue?vue&type=script&lang=js& */ "./resources/js/Pages/User/ChangePassword.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ChangePassword_vue_vue_type_template_id_325f6094___WEBPACK_IMPORTED_MODULE_0__.render,
  _ChangePassword_vue_vue_type_template_id_325f6094___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/User/ChangePassword.vue"
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

/***/ "./resources/js/Pages/User/ChangePassword.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/User/ChangePassword.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ChangePassword.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/User/ChangePassword.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

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

/***/ "./resources/js/Pages/User/ChangePassword.vue?vue&type=template&id=325f6094&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Pages/User/ChangePassword.vue?vue&type=template&id=325f6094& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_template_id_325f6094___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_template_id_325f6094___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ChangePassword_vue_vue_type_template_id_325f6094___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ChangePassword.vue?vue&type=template&id=325f6094& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/User/ChangePassword.vue?vue&type=template&id=325f6094&");


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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/User/ChangePassword.vue?vue&type=template&id=325f6094&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/User/ChangePassword.vue?vue&type=template&id=325f6094& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
      _c("div", { staticClass: "col-md-6 grid-margin stretch-card" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c("div", { staticClass: "card-title" }, [
                _vm._v("Please Enter New Password"),
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
                            _c("text-input", {
                              attrs: {
                                type: "password",
                                rules: "required",
                                label: "Current Password",
                                name: "current_password",
                                placeholder: "Enter Current Password",
                                autofocus: "",
                              },
                              model: {
                                value: _vm.form.current_password,
                                callback: function ($$v) {
                                  _vm.$set(_vm.form, "current_password", $$v)
                                },
                                expression: "form.current_password",
                              },
                            }),
                            _vm._v(" "),
                            _c("text-input", {
                              attrs: {
                                type: "password",
                                rules: "required|confirmed:confirmation|min:6",
                                label: "New Password",
                                name: "password",
                                placeholder: "Enter New Password",
                              },
                              model: {
                                value: _vm.form.password,
                                callback: function ($$v) {
                                  _vm.$set(_vm.form, "password", $$v)
                                },
                                expression: "form.password",
                              },
                            }),
                            _vm._v(" "),
                            _c("text-input", {
                              attrs: {
                                type: "password",
                                rules: "required",
                                vid: "confirmation",
                                label: "Confirm Password",
                                name: "password_confirmation",
                                placeholder: "Confirm New Password",
                              },
                              model: {
                                value: _vm.form.password_confirmation,
                                callback: function ($$v) {
                                  _vm.$set(
                                    _vm.form,
                                    "password_confirmation",
                                    $$v
                                  )
                                },
                                expression: "form.password_confirmation",
                              },
                            }),
                            _vm._v(" "),
                            _c("submit-button", {
                              attrs: {
                                button_text: "Reset Password",
                                submitted: _vm.submitted,
                              },
                            }),
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
          _vm._v("Change Password"),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ })

}]);