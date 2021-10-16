"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_TechTips_Index_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=script&lang=js& ***!
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: {//
  },
  data: function data() {
    return {
      //
      showNav: false,
      alert: {
        type: null,
        message: null
      }
    };
  },
  created: function created() {//
  },
  mounted: function mounted() {
    var _this = this;

    //  Manually trigger alert from Vue Component
    this.eventHub.$on('show-alert', function (alert) {
      _this.alert.message = alert.message;
      _this.alert.type = alert.type;
    }); //  Manually cancel alert that was triggered

    this.eventHub.$on('clear-alert', function () {
      _this.alert.message = null;
      _this.alert.type = null;
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
  watch: {//
  },
  methods: {//
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Index.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Index.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  layout: _Layouts_app__WEBPACK_IMPORTED_MODULE_0__.default,
  props: {
    filter_data: {
      type: Object,
      required: true
    },
    create: {
      type: Boolean,
      "default": false
    }
  },
  data: function data() {
    return {
      showOverlay: false,
      loading: false,
      error: false,
      tech_tips: [],
      results: {
        total: 0,
        low: 0,
        high: 0,
        per_page: [10, 25, 50, 100]
      },
      form: {
        search_text: null,
        search_type: [],
        search_equip_id: [],
        pagination_perPage: 10,
        page: 1
      },
      fields: [{
        key: 'pinned',
        label: '',
        sortable: false
      }, {
        key: 'preview',
        label: '',
        sortable: false
      }, {
        key: 'subject',
        label: 'Subject',
        sortable: true
      }, {
        key: 'created_at',
        label: 'Date',
        sortable: true
      }]
    };
  },
  created: function created() {//
  },
  mounted: function mounted() {
    this.search();
  },
  computed: {//
  },
  watch: {//
  },
  methods: {
    search: function search() {
      var _this = this;

      this.loading = true;
      axios.get(this.route('tips.search', this.form)).then(function (res) {
        console.log(res.data);
        _this.tech_tips = res.data.data;
        _this.results.total = res.data.total;
        _this.results.low = res.data.from;
        _this.results.high = res.data.to;
        _this.loading = false;
      })["catch"](function (error) {
        return _this.eventHub.$emit('axiosError', error);
      });
    },
    resetFilters: function resetFilters() {
      this.form = {
        search_text: null,
        search_type: [],
        search_equip_id: [],
        pagination_perPage: 10,
        page: 1
      };
      this.search();
    },
    updatePage: function updatePage(newPage) {
      this.form.page = newPage;
      this.search();
    },
    updatePerPage: function updatePerPage(num) {
      this.form.pagination_perPage = num;
      this.search();
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
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
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

/***/ "./resources/js/Pages/TechTips/Index.vue":
/*!***********************************************!*\
  !*** ./resources/js/Pages/TechTips/Index.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Index_vue_vue_type_template_id_ab1af08c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=ab1af08c& */ "./resources/js/Pages/TechTips/Index.vue?vue&type=template&id=ab1af08c&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/TechTips/Index.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _Index_vue_vue_type_template_id_ab1af08c___WEBPACK_IMPORTED_MODULE_0__.render,
  _Index_vue_vue_type_template_id_ab1af08c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/TechTips/Index.vue"
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
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Pages/TechTips/Index.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/Pages/TechTips/Index.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Index.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

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

/***/ "./resources/js/Pages/TechTips/Index.vue?vue&type=template&id=ab1af08c&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/TechTips/Index.vue?vue&type=template&id=ab1af08c& ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_ab1af08c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_ab1af08c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_ab1af08c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Index.vue?vue&type=template&id=ab1af08c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Index.vue?vue&type=template&id=ab1af08c&");


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
              staticClass: "navbar-toggler d-lg-none",
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Index.vue?vue&type=template&id=ab1af08c&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Index.vue?vue&type=template&id=ab1af08c& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
    _c("div", { staticClass: "row grid-margin" }, [
      _c("div", { staticClass: "col-12" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c(
                "b-form",
                {
                  on: {
                    submit: function($event) {
                      $event.preventDefault()
                      return _vm.search.apply(null, arguments)
                    }
                  }
                },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-form-input", {
                        attrs: {
                          type: "text",
                          placeholder: "Search Tips...",
                          autofocus: ""
                        },
                        model: {
                          value: _vm.form.search_text,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "search_text", $$v)
                          },
                          expression: "form.search_text"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "b-input-group-append",
                        [
                          _c(
                            "b-button",
                            { attrs: { type: "submit", variant: "primary" } },
                            [
                              _c("span", { staticClass: "fas fa-search" }),
                              _vm._v(" "),
                              _c(
                                "span",
                                { staticClass: "d-none d-sm-inline" },
                                [_vm._v("Search")]
                              )
                            ]
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm.create
                        ? _c(
                            "b-input-group-append",
                            [
                              _c(
                                "inertia-link",
                                {
                                  staticClass:
                                    "btn btn-warning d-none d-sm-block",
                                  attrs: { href: _vm.route("tech-tips.create") }
                                },
                                [
                                  _c("span", { staticClass: "fas fa-plus" }),
                                  _vm._v(" "),
                                  _c(
                                    "span",
                                    { staticClass: "d-none d-sm-inline" },
                                    [_vm._v("Create New")]
                                  )
                                ]
                              )
                            ],
                            1
                          )
                        : _vm._e()
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.create
                    ? _c("div", { staticClass: "text-center mt-2 d-sm-none" }, [
                        _c(
                          "a",
                          {
                            staticClass: "btn btn-warning",
                            attrs: { href: _vm.route("tech-tips.create") }
                          },
                          [
                            _c("span", { staticClass: "fas fa-plus" }),
                            _vm._v(" "),
                            _c("span", [_vm._v("Create New")])
                          ]
                        )
                      ])
                    : _vm._e()
                ],
                1
              )
            ],
            1
          )
        ])
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row grid-margin" }, [
      _c("div", { staticClass: "col-lg-3 col-12 stretch-card" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c(
                "div",
                { staticClass: "card-title" },
                [
                  _vm._v(
                    "\n                        Filter Options\n                        "
                  ),
                  _c(
                    "b-button",
                    {
                      directives: [
                        {
                          name: "b-toggle",
                          rawName: "v-b-toggle.filter-options-collapse",
                          modifiers: { "filter-options-collapse": true }
                        }
                      ],
                      staticClass: "float-right d-block d-lg-none",
                      attrs: { size: "sm" }
                    },
                    [_c("i", { staticClass: "fas fa-bars" })]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "b-collapse",
                {
                  attrs: {
                    id: "filter-options-collapse",
                    "is-nav": "",
                    visible: ""
                  }
                },
                [
                  _c(
                    "b-overlay",
                    {
                      attrs: { show: _vm.showOverlay },
                      scopedSlots: _vm._u([
                        {
                          key: "overlay",
                          fn: function() {
                            return [_c("atom-loader")]
                          },
                          proxy: true
                        }
                      ])
                    },
                    [
                      _vm._v(" "),
                      _c(
                        "div",
                        [
                          _c("h6", { staticClass: "mt-4 mb-2" }, [
                            _vm._v("Article Type:")
                          ]),
                          _vm._v(" "),
                          _c(
                            "b-form-group",
                            [
                              _c("b-form-checkbox-group", {
                                attrs: {
                                  options: _vm.filter_data.tip_types,
                                  "text-field": "description",
                                  "value-field": "tip_type_id",
                                  stacked: ""
                                },
                                on: { change: _vm.search },
                                model: {
                                  value: _vm.form.search_type,
                                  callback: function($$v) {
                                    _vm.$set(_vm.form, "search_type", $$v)
                                  },
                                  expression: "form.search_type"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c("h6", { staticClass: "mt-4 mb-2" }, [
                            _vm._v("Equipment Type:")
                          ]),
                          _vm._v(" "),
                          _vm._l(_vm.filter_data.equip_types, function(cat) {
                            return _c(
                              "b-form-group",
                              { key: cat.cat_id, attrs: { label: cat.name } },
                              _vm._l(cat.equipment_type, function(equip) {
                                return _c(
                                  "b-form-checkbox",
                                  {
                                    key: equip.equip_id,
                                    attrs: {
                                      name: "equipment_type",
                                      value: equip.equip_id,
                                      stacked: ""
                                    },
                                    on: { change: _vm.search },
                                    model: {
                                      value: _vm.form.search_equip_id,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.form,
                                          "search_equip_id",
                                          $$v
                                        )
                                      },
                                      expression: "form.search_equip_id"
                                    }
                                  },
                                  [_vm._v(_vm._s(equip.name))]
                                )
                              }),
                              1
                            )
                          })
                        ],
                        2
                      ),
                      _vm._v(" "),
                      _c(
                        "b-button",
                        {
                          attrs: { variant: "info", block: "" },
                          on: { click: _vm.resetFilters }
                        },
                        [_vm._v("Reset Filters")]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-lg-9 col-12 stretch-card" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c("b-table", {
                attrs: {
                  items: _vm.tech_tips,
                  fields: _vm.fields,
                  striped: "",
                  responsive: "",
                  busy: _vm.loading,
                  "show-empty": ""
                },
                scopedSlots: _vm._u([
                  {
                    key: "empty",
                    fn: function() {
                      return [
                        _c("h5", { staticClass: "text-center" }, [
                          _vm._v("Nothing to See Here")
                        ]),
                        _vm._v(" "),
                        _c("p", { staticClass: "text-center" }, [
                          _vm._v(
                            "No Tech Tips found.  Try searching for something else"
                          )
                        ])
                      ]
                    },
                    proxy: true
                  },
                  {
                    key: "table-busy",
                    fn: function() {
                      return [_c("atom-loader")]
                    },
                    proxy: true
                  },
                  {
                    key: "cell(subject)",
                    fn: function(data) {
                      return [
                        _c(
                          "inertia-link",
                          {
                            attrs: {
                              href: _vm.route("tech-tips.show", data.item.slug)
                            }
                          },
                          [_vm._v(_vm._s(data.item.subject))]
                        )
                      ]
                    }
                  },
                  {
                    key: "cell(preview)",
                    fn: function(data) {
                      return [
                        _c("i", {
                          directives: [
                            {
                              name: "b-tooltip",
                              rawName: "v-b-tooltip.hover",
                              modifiers: { hover: true }
                            }
                          ],
                          staticClass: "pointer fas",
                          class: data.detailsShowing
                            ? "fa-eye-slash"
                            : "fa-eye",
                          attrs: {
                            title: data.detailsShowing
                              ? "Hide Preview"
                              : "Show Preview"
                          },
                          on: { click: data.toggleDetails }
                        })
                      ]
                    }
                  },
                  {
                    key: "cell(pinned)",
                    fn: function(data) {
                      return [
                        data.item.sticky
                          ? _c("i", {
                              directives: [
                                {
                                  name: "b-tooltip",
                                  rawName: "v-b-tooltip.hover",
                                  modifiers: { hover: true }
                                }
                              ],
                              staticClass: "fas fa-thumbtack text-danger",
                              attrs: { title: "Pinned Tip" }
                            })
                          : _vm._e()
                      ]
                    }
                  },
                  {
                    key: "row-details",
                    fn: function(data) {
                      return [
                        _c("div", {
                          domProps: { innerHTML: _vm._s(data.item.summary) }
                        }),
                        _vm._v(" "),
                        _c(
                          "div",
                          [
                            _c("strong", [_vm._v("For Equipment:")]),
                            _vm._v(" "),
                            _vm._l(data.item.equipment_type, function(equip) {
                              return _c(
                                "b-badge",
                                {
                                  key: equip.equip_id,
                                  attrs: { pill: "", variant: "primary" }
                                },
                                [_vm._v(_vm._s(equip.name))]
                              )
                            })
                          ],
                          2
                        )
                      ]
                    }
                  }
                ])
              }),
              _vm._v(" "),
              !_vm.loading
                ? _c("div", { staticClass: "row" }, [
                    _c(
                      "div",
                      { staticClass: "col-sm-3 text-center text-sm-left" },
                      [
                        _vm._v(
                          "\n                            " +
                            _vm._s(_vm.results.low) +
                            " through " +
                            _vm._s(_vm.results.high) +
                            " of " +
                            _vm._s(_vm.results.total) +
                            "\n                        "
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "col-sm-6" },
                      [
                        _c("b-pagination", {
                          attrs: {
                            "total-rows": _vm.results.total,
                            "per-page": _vm.form.pagination_perPage,
                            "next-text": "Next",
                            "prev-text": "Prev",
                            align: "center"
                          },
                          on: { change: _vm.updatePage },
                          model: {
                            value: _vm.form.page,
                            callback: function($$v) {
                              _vm.$set(_vm.form, "page", $$v)
                            },
                            expression: "form.page"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-sm-3" }, [
                      _vm._m(1),
                      _vm._v(" "),
                      _c("div", { staticClass: "row" }, [
                        _c(
                          "div",
                          { staticClass: "col text-center" },
                          _vm._l(_vm.results.per_page, function(num) {
                            return _c(
                              "b-badge",
                              {
                                key: num,
                                staticClass: "pointer ml-1 mb-1",
                                attrs: {
                                  pill: "",
                                  variant:
                                    _vm.form.pagination_perPage == num
                                      ? "success"
                                      : "primary"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.updatePerPage(num)
                                  }
                                }
                              },
                              [
                                _vm._v(
                                  "\n                                        " +
                                    _vm._s(num) +
                                    "\n                                    "
                                )
                              ]
                            )
                          }),
                          1
                        )
                      ])
                    ])
                  ])
                : _vm._e()
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
    return _c("div", { staticClass: "row grid-margin" }, [
      _c("div", { staticClass: "col-md-12" }, [
        _c("h4", { staticClass: "text-center text-md-left" }, [
          _vm._v("Tech Tips")
        ])
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col text-center" }, [
        _vm._v(
          "\n                                    Results Per Page\n                                "
        )
      ])
    ])
  }
]
render._withStripped = true



/***/ })

}]);