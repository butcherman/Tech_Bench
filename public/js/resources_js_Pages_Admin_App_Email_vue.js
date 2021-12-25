"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Admin_App_Email_vue"],{

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

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      showNav: false,
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
    }
  },
  metaInfo: {
    title: 'Welcome',
    titleTemplate: '%s | Tech Bench'
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Admin/App/Email.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Admin/App/Email.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************/
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

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  layout: _Layouts_app__WEBPACK_IMPORTED_MODULE_0__["default"],
  props: {
    settings: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      submitted: false,
      sending: false,
      form: this.$inertia.form({
        from_address: this.settings.from_address,
        username: this.settings.username,
        password: this.settings.username ? 'RandomString' : null,
        host: this.settings.host,
        port: this.settings.port,
        encryption: this.settings.encryption,
        authentication: this.settings.username != null ? true : false
      }),
      encryptionOptions: [{
        value: 'tls',
        text: 'TLS'
      }, {
        value: 'ssl',
        text: 'SSL'
      }, {
        value: 'none',
        text: 'None'
      }]
    };
  },
  methods: {
    submitForm: function submitForm() {
      var _this = this;

      this.submitted = true;
      this.form.post(route('admin.set-email'), {
        onFinish: function onFinish() {
          _this.submitted = false;
        }
      });
    },
    sendTestEmail: function sendTestEmail() {
      var _this2 = this;

      if (this.form.isDirty) {
        this.$bvModal.msgBoxOk('Email Settings have changed.  Please save before sending test email');
      } else {
        this.sending = true;
        axios.get(route('admin.test-email')).then(function (res) {
          _this2.sending = false;

          if (res.data.success) {
            _this2.$bvModal.msgBoxOk('Test Email Sent');
          } else {
            _this2.$bvModal.msgBoxOk('Response from server - ' + res.data.message);
          }
        })["catch"](function (error) {
          return _this2.eventHub.$emit('axiosError', error);
        });
      }
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

/***/ "./resources/js/Pages/Admin/App/Email.vue":
/*!************************************************!*\
  !*** ./resources/js/Pages/Admin/App/Email.vue ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Email_vue_vue_type_template_id_992c3cd6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Email.vue?vue&type=template&id=992c3cd6& */ "./resources/js/Pages/Admin/App/Email.vue?vue&type=template&id=992c3cd6&");
/* harmony import */ var _Email_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Email.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Admin/App/Email.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Email_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Email_vue_vue_type_template_id_992c3cd6___WEBPACK_IMPORTED_MODULE_0__.render,
  _Email_vue_vue_type_template_id_992c3cd6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Admin/App/Email.vue"
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

/***/ "./resources/js/Pages/Admin/App/Email.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/Pages/Admin/App/Email.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Email_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Email.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Admin/App/Email.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Email_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

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

/***/ "./resources/js/Pages/Admin/App/Email.vue?vue&type=template&id=992c3cd6&":
/*!*******************************************************************************!*\
  !*** ./resources/js/Pages/Admin/App/Email.vue?vue&type=template&id=992c3cd6& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Email_vue_vue_type_template_id_992c3cd6___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Email_vue_vue_type_template_id_992c3cd6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Email_vue_vue_type_template_id_992c3cd6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Email.vue?vue&type=template&id=992c3cd6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Admin/App/Email.vue?vue&type=template&id=992c3cd6&");


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
var render = function() {
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
              attrs: { href: _vm.route("dashboard") }
            },
            [
              _c("img", {
                staticClass: "mr-2",
                attrs: { src: _vm.app.logo, alt: _vm.app.name }
              })
            ]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c("div", { staticClass: "navbar-brand d-none d-md-flex" }, [
        _c("h2", [_vm._v(_vm._s(_vm.app.name))])
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
                  modifiers: { hover: true }
                }
              ],
              staticClass: "text-muted",
              attrs: {
                href: _vm.route("about"),
                title: "About " + _vm.app.name
              }
            },
            [_c("i", { staticClass: "fas fa-info-circle" })]
          ),
          _vm._v(" "),
          _c(
            "b-dropdown",
            {
              directives: [
                {
                  name: "b-tooltip",
                  rawName: "v-b-tooltip.hover",
                  modifiers: { hover: true }
                }
              ],
              attrs: { variant: "link", title: "Account" },
              scopedSlots: _vm._u([
                {
                  key: "button-content",
                  fn: function() {
                    return [
                      _c("b-avatar", {
                        attrs: {
                          variant: "warning",
                          text: _vm.app.user.initials
                        }
                      })
                    ]
                  },
                  proxy: true
                }
              ])
            },
            [
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  attrs: {
                    as: "b-dropdown-item",
                    href: _vm.route("settings.index")
                  }
                },
                [_c("i", { staticClass: "fas fa-cog" }), _vm._v(" Settings")]
              ),
              _vm._v(" "),
              _c(
                "inertia-link",
                {
                  attrs: {
                    as: "b-dropdown-item",
                    href: _vm.route("password.index")
                  }
                },
                [
                  _c("i", { staticClass: "fas fa-key" }),
                  _vm._v(" Change Password")
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
                    href: _vm.route("logout")
                  }
                },
                [
                  _c("i", { staticClass: "fas fa-sign-out-alt" }),
                  _vm._v(" Logout")
                ]
              )
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
                click: function($event) {
                  _vm.showNav = !_vm.showNav
                }
              }
            },
            [_c("i", { staticClass: "fas fa-bars" })]
          )
        ],
        1
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "container-fluid page-body-wrapper" }, [
      _c(
        "nav",
        {
          staticClass: "sidebar sidebar-nav",
          class: _vm.navbarActive,
          attrs: { id: "side-nav" }
        },
        [
          _c(
            "ul",
            { staticClass: "nav" },
            _vm._l(_vm.navbar, function(l) {
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
                        _vm._v(_vm._s(l.name))
                      ])
                    ]
                  )
                ],
                1
              )
            }),
            0
          )
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
              _c(
                "b-alert",
                {
                  attrs: {
                    variant: _vm.$page.props.flash.type,
                    show: _vm.$page.props.flash.message ? 30 : false
                  }
                },
                [
                  _c("p", { staticClass: "text-center" }, [
                    _vm._v(_vm._s(_vm.$page.props.flash.message))
                  ])
                ]
              ),
              _vm._v(" "),
              _c(
                "b-alert",
                {
                  attrs: {
                    variant: _vm.alert.type,
                    show: _vm.alert.message ? 30 : false
                  }
                },
                [
                  _c("p", { staticClass: "text-center" }, [
                    _vm._v(_vm._s(_vm.alert.message))
                  ])
                ]
              ),
              _vm._v(" "),
              _vm._t("default")
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
                  "d-sm-flex justify-content-center justify-content-sm-between"
              },
              [
                _vm._m(0),
                _vm._v(" "),
                _c(
                  "span",
                  {
                    staticClass:
                      "text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center"
                  },
                  [_vm._v(_vm._s(_vm.app.version))]
                )
              ]
            )
          ])
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "span",
      {
        staticClass:
          "text-muted text-center text-sm-left d-block d-sm-inline-block"
      },
      [
        _vm._v("Copyright © 2016-2021"),
        _c("span", { staticClass: "d-none d-md-inline" }, [
          _vm._v(" Butcherman - All rights reserved.")
        ])
      ]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Admin/App/Email.vue?vue&type=template&id=992c3cd6&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Admin/App/Email.vue?vue&type=template&id=992c3cd6& ***!
  \**********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
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
              _c("ValidationObserver", {
                scopedSlots: _vm._u([
                  {
                    key: "default",
                    fn: function(ref) {
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
                                  fn: function() {
                                    return [
                                      _c("form-loader", {
                                        attrs: { text: "Processing..." }
                                      })
                                    ]
                                  },
                                  proxy: true
                                }
                              ],
                              null,
                              true
                            )
                          },
                          [
                            _vm._v(" "),
                            _c(
                              "b-form",
                              {
                                attrs: { novalidate: "" },
                                on: {
                                  submit: function($event) {
                                    $event.preventDefault()
                                    return handleSubmit(_vm.submitForm)
                                  }
                                }
                              },
                              [
                                _c("text-input", {
                                  attrs: {
                                    type: "email",
                                    rules: "required|email",
                                    label: "From Email Address",
                                    placeholder: "no-reply@example.com"
                                  },
                                  model: {
                                    value: _vm.form.from_address,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "from_address", $$v)
                                    },
                                    expression: "form.from_address"
                                  }
                                }),
                                _vm._v(" "),
                                _c("text-input", {
                                  attrs: {
                                    type: "text",
                                    rules: "required",
                                    label: "Host",
                                    placeholder: "smtp.example.com"
                                  },
                                  model: {
                                    value: _vm.form.host,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "host", $$v)
                                    },
                                    expression: "form.host"
                                  }
                                }),
                                _vm._v(" "),
                                _c("text-input", {
                                  attrs: {
                                    type: "number",
                                    rules: "required",
                                    label: "Port",
                                    placeholder: "25"
                                  },
                                  model: {
                                    value: _vm.form.port,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "port", $$v)
                                    },
                                    expression: "form.port"
                                  }
                                }),
                                _vm._v(" "),
                                _c("dropdown-input", {
                                  attrs: {
                                    ruled: "required",
                                    label: "Encryption Type",
                                    options: _vm.encryptionOptions
                                  },
                                  model: {
                                    value: _vm.form.encryption,
                                    callback: function($$v) {
                                      _vm.$set(_vm.form, "encryption", $$v)
                                    },
                                    expression: "form.encryption"
                                  }
                                }),
                                _vm._v(" "),
                                _c(
                                  "b-form-checkbox",
                                  {
                                    staticClass: "text-center",
                                    attrs: { switch: "" },
                                    model: {
                                      value: _vm.form.authentication,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.form,
                                          "authentication",
                                          $$v
                                        )
                                      },
                                      expression: "form.authentication"
                                    }
                                  },
                                  [
                                    _vm._v(
                                      "\n                                    Authentication\n                                "
                                    )
                                  ]
                                ),
                                _vm._v(" "),
                                _c("transition", { attrs: { name: "fade" } }, [
                                  _vm.form.authentication
                                    ? _c(
                                        "div",
                                        [
                                          _c("text-input", {
                                            attrs: {
                                              type: "text",
                                              rules: "required",
                                              label: "Email Username",
                                              placeholder: "Enter Username"
                                            },
                                            model: {
                                              value: _vm.form.username,
                                              callback: function($$v) {
                                                _vm.$set(
                                                  _vm.form,
                                                  "username",
                                                  $$v
                                                )
                                              },
                                              expression: "form.username"
                                            }
                                          }),
                                          _vm._v(" "),
                                          _c("text-input", {
                                            attrs: {
                                              type: "password",
                                              rules: "required",
                                              label: "Email Password",
                                              placeholder: "Enter Password"
                                            },
                                            model: {
                                              value: _vm.form.password,
                                              callback: function($$v) {
                                                _vm.$set(
                                                  _vm.form,
                                                  "password",
                                                  $$v
                                                )
                                              },
                                              expression: "form.password"
                                            }
                                          })
                                        ],
                                        1
                                      )
                                    : _vm._e()
                                ]),
                                _vm._v(" "),
                                _c("submit-button", {
                                  staticClass: "mt-3",
                                  attrs: {
                                    button_text: "Save Email Settings",
                                    submitted: _vm.submitted
                                  }
                                })
                              ],
                              1
                            )
                          ],
                          1
                        )
                      ]
                    }
                  }
                ])
              }),
              _vm._v(" "),
              _c(
                "b-button",
                {
                  staticClass: "mt-3",
                  attrs: {
                    variant: "warning",
                    block: "",
                    disabled: _vm.sending
                  },
                  on: { click: _vm.sendTestEmail }
                },
                [
                  _vm.sending
                    ? _c("span", [
                        _c("span", {
                          staticClass:
                            "spinner-border spinner-border-sm text-danger"
                        }),
                        _vm._v(
                          "\n                            Sending....\n                        "
                        )
                      ])
                    : _c("span", [_vm._v("Send Test Email")])
                ]
              )
            ],
            1
          )
        ])
      ])
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-12 grid-margin" }, [
        _c("h4", { staticClass: "text-center text-md-left" }, [
          _vm._v("Email Settings")
        ])
      ])
    ])
  }
]
render._withStripped = true



/***/ })

}]);