"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Auth_password_reset_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/Nested/authLayout.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/Nested/authLayout.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
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
  metaInfo: {
    title: 'Welcome'
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Auth/password/reset.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Auth/password/reset.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Layouts_guest__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../Layouts/guest */ "./resources/js/Layouts/guest.vue");
/* harmony import */ var _Layouts_Nested_authLayout__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../Layouts/Nested/authLayout */ "./resources/js/Layouts/Nested/authLayout.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  layout: [_Layouts_guest__WEBPACK_IMPORTED_MODULE_0__["default"], _Layouts_Nested_authLayout__WEBPACK_IMPORTED_MODULE_1__["default"]],
  props: {
    email: {
      type: String,
      required: true
    },
    token: {
      type: String,
      required: true
    },
    errors: {
      type: Object
    }
  },
  data: function data() {
    return {
      form: {
        token: this.token,
        email: this.email,
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
      this.$inertia.post(route('password.reset-submit'), this.form, {
        onFinish: function onFinish() {
          _this.submitted = false;
        }
      });
    }
  },
  metaInfo: {
    title: 'Reset Password'
  }
});

/***/ }),

/***/ "./resources/js/Layouts/Nested/authLayout.vue":
/*!****************************************************!*\
  !*** ./resources/js/Layouts/Nested/authLayout.vue ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _authLayout_vue_vue_type_template_id_2ec4f636___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./authLayout.vue?vue&type=template&id=2ec4f636& */ "./resources/js/Layouts/Nested/authLayout.vue?vue&type=template&id=2ec4f636&");
/* harmony import */ var _authLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./authLayout.vue?vue&type=script&lang=js& */ "./resources/js/Layouts/Nested/authLayout.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _authLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _authLayout_vue_vue_type_template_id_2ec4f636___WEBPACK_IMPORTED_MODULE_0__.render,
  _authLayout_vue_vue_type_template_id_2ec4f636___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Layouts/Nested/authLayout.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Auth/password/reset.vue":
/*!****************************************************!*\
  !*** ./resources/js/Pages/Auth/password/reset.vue ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _reset_vue_vue_type_template_id_09ca55ee___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./reset.vue?vue&type=template&id=09ca55ee& */ "./resources/js/Pages/Auth/password/reset.vue?vue&type=template&id=09ca55ee&");
/* harmony import */ var _reset_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reset.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Auth/password/reset.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _reset_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _reset_vue_vue_type_template_id_09ca55ee___WEBPACK_IMPORTED_MODULE_0__.render,
  _reset_vue_vue_type_template_id_09ca55ee___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Auth/password/reset.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Layouts/Nested/authLayout.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Layouts/Nested/authLayout.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_authLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./authLayout.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/Nested/authLayout.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_authLayout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Auth/password/reset.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/Auth/password/reset.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_reset_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./reset.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Auth/password/reset.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_reset_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Layouts/Nested/authLayout.vue?vue&type=template&id=2ec4f636&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Layouts/Nested/authLayout.vue?vue&type=template&id=2ec4f636& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_authLayout_vue_vue_type_template_id_2ec4f636___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_authLayout_vue_vue_type_template_id_2ec4f636___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_authLayout_vue_vue_type_template_id_2ec4f636___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./authLayout.vue?vue&type=template&id=2ec4f636& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/Nested/authLayout.vue?vue&type=template&id=2ec4f636&");


/***/ }),

/***/ "./resources/js/Pages/Auth/password/reset.vue?vue&type=template&id=09ca55ee&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Pages/Auth/password/reset.vue?vue&type=template&id=09ca55ee& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_reset_vue_vue_type_template_id_09ca55ee___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_reset_vue_vue_type_template_id_09ca55ee___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_reset_vue_vue_type_template_id_09ca55ee___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./reset.vue?vue&type=template&id=09ca55ee& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Auth/password/reset.vue?vue&type=template&id=09ca55ee&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/Nested/authLayout.vue?vue&type=template&id=2ec4f636&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/Nested/authLayout.vue?vue&type=template&id=2ec4f636& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
  return _c(
    "div",
    {
      staticClass: "row justify-content-center align-items-center",
      attrs: { id: "auth-layout-container" }
    },
    [
      _c("div", { staticClass: "col" }, [
        _c("div", { staticClass: "row justify-content-center" }, [
          _c("h1", [_vm._v(_vm._s(_vm.$page.props.app.name))])
        ]),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass: "row justify-content-center",
            attrs: { id: "auth-layout-sub-container" }
          },
          [
            _c("div", { staticClass: "col col-md-3 col-10" }, [
              _c("img", {
                attrs: {
                  src: _vm.$page.props.app.logo,
                  alt: "Company Logo",
                  id: "header-logo"
                }
              })
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col col-md-3 col-10" },
              [_vm._t("default")],
              2
            )
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Auth/password/reset.vue?vue&type=template&id=09ca55ee&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Auth/password/reset.vue?vue&type=template&id=09ca55ee& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "row h-100 align-items-center" }, [
    _c(
      "div",
      { staticClass: "col-12" },
      [
        _c("ValidationObserver", {
          scopedSlots: _vm._u([
            {
              key: "default",
              fn: function(ref) {
                var handleSubmit = ref.handleSubmit
                return [
                  _c("h6", { staticClass: "text-center" }, [
                    _vm._v("Reset Password")
                  ]),
                  _vm._v(" "),
                  _vm._l(_vm.errors, function(e) {
                    return _c(
                      "b-alert",
                      {
                        key: e,
                        attrs: {
                          variant: "danger",
                          show: _vm.errors ? true : false
                        }
                      },
                      [
                        _c("p", { staticClass: "text-center" }, [
                          _vm._v(_vm._s(e))
                        ])
                      ]
                    )
                  }),
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
                          rules: "required|email",
                          label: "Email",
                          name: "email",
                          value: _vm.form.email
                        },
                        model: {
                          value: _vm.form.email,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "email", $$v)
                          },
                          expression: "form.email"
                        }
                      }),
                      _vm._v(" "),
                      _c("text-input", {
                        attrs: {
                          type: "password",
                          rules: "required|confirmed:confirmation|min:6",
                          label: "New Password",
                          name: "password",
                          placeholder: "Enter New Password"
                        },
                        model: {
                          value: _vm.form.password,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "password", $$v)
                          },
                          expression: "form.password"
                        }
                      }),
                      _vm._v(" "),
                      _c("text-input", {
                        attrs: {
                          type: "password",
                          rules: "required",
                          vid: "confirmation",
                          label: "Confirm Password",
                          name: "password_confirmation",
                          placeholder: "Confirm New Password"
                        },
                        model: {
                          value: _vm.form.password_confirmation,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "password_confirmation", $$v)
                          },
                          expression: "form.password_confirmation"
                        }
                      }),
                      _vm._v(" "),
                      _c("submit-button", {
                        staticClass: "mb-2",
                        attrs: {
                          button_text: "Reset Password",
                          submitted: _vm.submitted
                        }
                      })
                    ],
                    1
                  )
                ]
              }
            }
          ])
        })
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);