"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Error_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Error.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Error.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    status: {
      type: Number,
      required: true
    },
    message: {
      type: String,
      required: false,
      "default": null
    }
  },
  computed: {
    title: function title() {
      return {
        503: '503 | Service Unavailable',
        500: '500 | Server Error',
        404: '404 | Page Not Found',
        403: '403 | Forbidden'
      }[this.status];
    },
    description: function description() {
      if (this.message !== null) {
        return this.message;
      }

      return {
        503: 'Sorry, we are doing some maintenance behind the curtain. Please check back soon.',
        500: 'Whoops, something bad happened.  Our minions are hard at work to determine what went wrong',
        404: 'I cannot seem to find the page you are looking for',
        403: 'Sorry, you do not appear to belong here'
      }[this.status];
    }
  }
});

/***/ }),

/***/ "./resources/js/Pages/Error.vue":
/*!**************************************!*\
  !*** ./resources/js/Pages/Error.vue ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Error_vue_vue_type_template_id_7a1c0e2f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Error.vue?vue&type=template&id=7a1c0e2f& */ "./resources/js/Pages/Error.vue?vue&type=template&id=7a1c0e2f&");
/* harmony import */ var _Error_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Error.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Error.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Error_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Error_vue_vue_type_template_id_7a1c0e2f___WEBPACK_IMPORTED_MODULE_0__.render,
  _Error_vue_vue_type_template_id_7a1c0e2f___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Error.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/Error.vue?vue&type=script&lang=js&":
/*!***************************************************************!*\
  !*** ./resources/js/Pages/Error.vue?vue&type=script&lang=js& ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Error_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Error.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Error.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Error_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Error.vue?vue&type=template&id=7a1c0e2f&":
/*!*********************************************************************!*\
  !*** ./resources/js/Pages/Error.vue?vue&type=template&id=7a1c0e2f& ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Error_vue_vue_type_template_id_7a1c0e2f___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Error_vue_vue_type_template_id_7a1c0e2f___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Error_vue_vue_type_template_id_7a1c0e2f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Error.vue?vue&type=template&id=7a1c0e2f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Error.vue?vue&type=template&id=7a1c0e2f&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Error.vue?vue&type=template&id=7a1c0e2f&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Error.vue?vue&type=template&id=7a1c0e2f& ***!
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
  return _c(
    "div",
    {
      staticClass: "row justify-content-center align-items-center",
      attrs: { id: "auth-layout-container" },
    },
    [
      _c("div", { staticClass: "col-md-10" }, [
        _c("h1", { staticClass: "text-center" }, [_vm._v(_vm._s(_vm.title))]),
        _vm._v(" "),
        _c("h4", { staticClass: "text-center mt-4" }, [
          _vm._v(_vm._s(_vm.description)),
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "text-center mt-4" },
          [
            _c("inertia-link", { attrs: { as: "b-button", href: "/" } }, [
              _vm._v("Return Home"),
            ]),
          ],
          1
        ),
      ]),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);