"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_TechTips_Show_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/discussion.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/discussion.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    comments: {
      type: Array,
      required: true
    },
    tip_id: {
      type: Number,
      required: true
    },
    permissions: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      // commentList: this.comments,
      submitted: false,
      loading: false,
      form: {
        tip_id: this.tip_id,
        comment: null
      },
      updateForm: {
        comment: null,
        comment_id: null
      }
    };
  },
  methods: {
    getFlaggedClass: function getFlaggedClass(comment) {
      return comment.flagged ? 'fas text-danger' : 'far pointer text-muted';
    },
    canEdit: function canEdit(comment) {
      return comment.user.username == this.$page.props.app.user.username;
    },
    canDelete: function canDelete(comment) {
      return comment.user.username == this.$page.props.app.user.username || this.permissions.manage;
    },
    flagComment: function flagComment(comment) {
      this.$inertia.get(route('tips.comments.edit', comment.id), {
        preserveScroll: true
      });
    },
    editComment: function editComment(comment) {
      this.updateForm.comment = comment.comment;
      this.updateForm.comment_id = comment.id;
      this.$refs['edit-comment-modal'].show();
    },
    deleteComment: function deleteComment(comment) {
      var _this = this;

      this.$bvModal.msgBoxConfirm('Please confirm you want to delete this comment.', {
        title: 'Are You Sure?',
        size: 'md',
        okVariant: 'danger',
        okTitle: 'Yes',
        cancelTitle: 'No',
        centered: true
      }).then(function (res) {
        if (res) {
          _this.$inertia["delete"](route('tips.comments.destroy', comment.id), {
            preserveScroll: true
          });
        }
      });
    },
    submitComment: function submitComment() {
      var _this2 = this;

      this.submitted = true;
      this.$inertia.post(route('tips.comments.store'), this.form, {
        preserveScroll: true,
        onSuccess: function onSuccess() {
          _this2.form.comment = null;
          _this2.submitted = false;

          _this2.$refs['validator'].reset();
        }
      });
    },
    updateComment: function updateComment() {
      var _this3 = this;

      this.loading = true;
      this.$inertia.put(route('tips.comments.update', this.updateForm.comment_id), this.updateForm, {
        preserveScroll: true,
        onSuccess: function onSuccess() {
          _this3.$refs['edit-comment-modal'].hide();

          _this3.loading = false;
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/manageTip.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/manageTip.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    permissions: {
      type: Object,
      required: true
    },
    tip_id: {
      type: Number,
      required: true
    }
  },
  data: function data() {
    return {
      loading: false,
      items: [{}]
    };
  },
  methods: {
    getDetails: function getDetails() {
      var _this = this;

      this.loading = true;
      axios.get(route('tips.details', this.tip_id)).then(function (res) {
        _this.items = [res.data];
        _this.loading = false;
      })["catch"](function (error) {
        return _this.eventHub.$emit('axiosError', error);
      });
    },
    deleteTip: function deleteTip() {
      var _this2 = this;

      this.$bvModal.msgBoxConfirm('Please Verify', {
        title: 'Are you sure you want to delete this Tech Tip?',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'danger',
        okTitle: 'YES',
        cancelTitle: 'NO',
        footerClass: 'p-2',
        hideHeaderClose: false,
        centered: true
      }).then(function (value) {
        if (value) {
          _this2.loading = true;

          _this2.$inertia["delete"](route('tech-tips.destroy', _this2.tip_id));
        }
      });
    }
  }
});

/***/ }),

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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Show.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Show.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Layouts_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../Layouts/app */ "./resources/js/Layouts/app.vue");
/* harmony import */ var _Components_TechTips_discussion_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Components/TechTips/discussion.vue */ "./resources/js/Components/TechTips/discussion.vue");
/* harmony import */ var _Components_TechTips_manageTip_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Components/TechTips/manageTip.vue */ "./resources/js/Components/TechTips/manageTip.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    manageTip: _Components_TechTips_manageTip_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
    Discussion: _Components_TechTips_discussion_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  layout: _Layouts_app__WEBPACK_IMPORTED_MODULE_0__["default"],
  props: {
    /**
     * object from /app/models/techtip
     */
    tip: {
      type: Object,
      required: true
    },

    /**
     * object from /app/models/user
     */
    user_data: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      is_fav: this.user_data.fav
    };
  },
  computed: {
    bookmark_class: function bookmark_class() {
      return this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
    },
    bookmark_title: function bookmark_title() {
      return this.is_fav ? 'Remove From Bookmarks' : 'Add to Bookmarks';
    }
  },
  methods: {
    /**
     * Ajax call to add or remove tip_id from user_tech_tip_bookmarks table
     */
    toggleFav: function toggleFav() {
      var _this = this;

      var form = {
        tip_id: this.tip.tip_id,
        state: !this.is_fav
      };
      axios.post(route('tips.bookmark'), form).then(function () {
        _this.is_fav = !_this.is_fav;
      })["catch"](function (error) {
        return _this.eventHub.$emit('axiosError', error);
      });
    }
  },
  metaInfo: {
    title: 'Tech Tip Details'
  }
});

/***/ }),

/***/ "./resources/js/Components/TechTips/discussion.vue":
/*!*********************************************************!*\
  !*** ./resources/js/Components/TechTips/discussion.vue ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _discussion_vue_vue_type_template_id_0e92f76e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./discussion.vue?vue&type=template&id=0e92f76e& */ "./resources/js/Components/TechTips/discussion.vue?vue&type=template&id=0e92f76e&");
/* harmony import */ var _discussion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./discussion.vue?vue&type=script&lang=js& */ "./resources/js/Components/TechTips/discussion.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _discussion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _discussion_vue_vue_type_template_id_0e92f76e___WEBPACK_IMPORTED_MODULE_0__.render,
  _discussion_vue_vue_type_template_id_0e92f76e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Components/TechTips/discussion.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Components/TechTips/manageTip.vue":
/*!********************************************************!*\
  !*** ./resources/js/Components/TechTips/manageTip.vue ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _manageTip_vue_vue_type_template_id_91b8e3c0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./manageTip.vue?vue&type=template&id=91b8e3c0& */ "./resources/js/Components/TechTips/manageTip.vue?vue&type=template&id=91b8e3c0&");
/* harmony import */ var _manageTip_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./manageTip.vue?vue&type=script&lang=js& */ "./resources/js/Components/TechTips/manageTip.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _manageTip_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _manageTip_vue_vue_type_template_id_91b8e3c0___WEBPACK_IMPORTED_MODULE_0__.render,
  _manageTip_vue_vue_type_template_id_91b8e3c0___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Components/TechTips/manageTip.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

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

/***/ "./resources/js/Pages/TechTips/Show.vue":
/*!**********************************************!*\
  !*** ./resources/js/Pages/TechTips/Show.vue ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Show_vue_vue_type_template_id_7f2d77c5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Show.vue?vue&type=template&id=7f2d77c5& */ "./resources/js/Pages/TechTips/Show.vue?vue&type=template&id=7f2d77c5&");
/* harmony import */ var _Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Show.vue?vue&type=script&lang=js& */ "./resources/js/Pages/TechTips/Show.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Show_vue_vue_type_template_id_7f2d77c5___WEBPACK_IMPORTED_MODULE_0__.render,
  _Show_vue_vue_type_template_id_7f2d77c5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/TechTips/Show.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Components/TechTips/discussion.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/Components/TechTips/discussion.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_discussion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./discussion.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/discussion.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_discussion_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Components/TechTips/manageTip.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/Components/TechTips/manageTip.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_manageTip_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./manageTip.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/manageTip.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_manageTip_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

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

/***/ "./resources/js/Pages/TechTips/Show.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/Pages/TechTips/Show.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Show.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Show.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Components/TechTips/discussion.vue?vue&type=template&id=0e92f76e&":
/*!****************************************************************************************!*\
  !*** ./resources/js/Components/TechTips/discussion.vue?vue&type=template&id=0e92f76e& ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_discussion_vue_vue_type_template_id_0e92f76e___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_discussion_vue_vue_type_template_id_0e92f76e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_discussion_vue_vue_type_template_id_0e92f76e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./discussion.vue?vue&type=template&id=0e92f76e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/discussion.vue?vue&type=template&id=0e92f76e&");


/***/ }),

/***/ "./resources/js/Components/TechTips/manageTip.vue?vue&type=template&id=91b8e3c0&":
/*!***************************************************************************************!*\
  !*** ./resources/js/Components/TechTips/manageTip.vue?vue&type=template&id=91b8e3c0& ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_manageTip_vue_vue_type_template_id_91b8e3c0___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_manageTip_vue_vue_type_template_id_91b8e3c0___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_manageTip_vue_vue_type_template_id_91b8e3c0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./manageTip.vue?vue&type=template&id=91b8e3c0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/manageTip.vue?vue&type=template&id=91b8e3c0&");


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

/***/ "./resources/js/Pages/TechTips/Show.vue?vue&type=template&id=7f2d77c5&":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/TechTips/Show.vue?vue&type=template&id=7f2d77c5& ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_template_id_7f2d77c5___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_template_id_7f2d77c5___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Show_vue_vue_type_template_id_7f2d77c5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Show.vue?vue&type=template&id=7f2d77c5& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Show.vue?vue&type=template&id=7f2d77c5&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/discussion.vue?vue&type=template&id=0e92f76e&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/discussion.vue?vue&type=template&id=0e92f76e& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "row justify-content-center" },
    [
      _c("div", { staticClass: "col-md-8 grid-margin" }, [
        _c("div", { staticClass: "card rounded" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c("div", { staticClass: "card-title" }, [
                _vm._v("\n                    Discussion:\n                "),
              ]),
              _vm._v(" "),
              _vm.comments.length == 0
                ? _c("div", [
                    _c("h5", { staticClass: "text-center" }, [
                      _vm._v("No Comments Yet"),
                    ]),
                  ])
                : _c(
                    "div",
                    { staticClass: "mb-4" },
                    _vm._l(_vm.comments, function (comment) {
                      return _c(
                        "div",
                        {
                          key: comment.comment_id,
                          staticClass: "border rounded p-4 mt-2",
                        },
                        [
                          _c("div", { staticClass: "mb-2" }, [
                            _c("span", { staticClass: "float-right" }, [
                              _c("i", {
                                directives: [
                                  {
                                    name: "b-tooltip",
                                    rawName: "v-b-tooltip.hover",
                                    modifiers: { hover: true },
                                  },
                                ],
                                staticClass: "fa-flag pl-2",
                                class: _vm.getFlaggedClass(comment),
                                attrs: { title: "Flag as Innappropriate" },
                                on: {
                                  click: function ($event) {
                                    return _vm.flagComment(comment)
                                  },
                                },
                              }),
                              _vm._v(" "),
                              _vm.canEdit(comment)
                                ? _c("i", {
                                    directives: [
                                      {
                                        name: "b-tooltip",
                                        rawName: "v-b-tooltip.hover",
                                        modifiers: { hover: true },
                                      },
                                    ],
                                    staticClass:
                                      "fas fa-pencil-alt pointer pl-2 text-muted",
                                    attrs: { title: "Edit Comment" },
                                    on: {
                                      click: function ($event) {
                                        return _vm.editComment(comment)
                                      },
                                    },
                                  })
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.canDelete(comment)
                                ? _c("i", {
                                    directives: [
                                      {
                                        name: "b-tooltip",
                                        rawName: "v-b-tooltip.hover",
                                        modifiers: { hover: true },
                                      },
                                    ],
                                    staticClass:
                                      "far fa-trash-alt text-danger pointer pl-2",
                                    attrs: { title: "Delete" },
                                    on: {
                                      click: function ($event) {
                                        return _vm.deleteComment(comment)
                                      },
                                    },
                                  })
                                : _vm._e(),
                            ]),
                            _vm._v(
                              "\n                            " +
                                _vm._s(comment.comment) +
                                "\n                        "
                            ),
                          ]),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "border-top text-secondary" },
                            [
                              _vm._v(
                                "\n                            " +
                                  _vm._s(comment.user.full_name) +
                                  "\n                            "
                              ),
                              _c("div", { staticClass: "float-right" }, [
                                _vm._v(_vm._s(comment.created_at)),
                              ]),
                            ]
                          ),
                        ]
                      )
                    }),
                    0
                  ),
              _vm._v(" "),
              _c(
                "b-overlay",
                {
                  attrs: { show: _vm.submitted },
                  scopedSlots: _vm._u([
                    {
                      key: "overlay",
                      fn: function () {
                        return [_c("atom-loader")]
                      },
                      proxy: true,
                    },
                  ]),
                },
                [
                  _vm._v(" "),
                  _c("ValidationObserver", {
                    ref: "validator",
                    scopedSlots: _vm._u([
                      {
                        key: "default",
                        fn: function (ref) {
                          var handleSubmit = ref.handleSubmit
                          return [
                            _vm.permissions.create
                              ? _c(
                                  "b-form",
                                  {
                                    attrs: { novalidate: "" },
                                    on: {
                                      submit: function ($event) {
                                        $event.preventDefault()
                                        return handleSubmit(_vm.submitComment)
                                      },
                                    },
                                  },
                                  [
                                    _c("ValidationProvider", {
                                      attrs: { rules: "required" },
                                      scopedSlots: _vm._u(
                                        [
                                          {
                                            key: "default",
                                            fn: function (v) {
                                              return [
                                                _c(
                                                  "b-form-group",
                                                  [
                                                    _c("b-form-textarea", {
                                                      attrs: {
                                                        placeholder:
                                                          "Comment on this Tech Tip...",
                                                        rows: "3",
                                                        "max-rows": "6",
                                                      },
                                                      model: {
                                                        value: _vm.form.comment,
                                                        callback: function (
                                                          $$v
                                                        ) {
                                                          _vm.$set(
                                                            _vm.form,
                                                            "comment",
                                                            $$v
                                                          )
                                                        },
                                                        expression:
                                                          "form.comment",
                                                      },
                                                    }),
                                                    _vm._v(" "),
                                                    _c(
                                                      "b-form-invalid-feedback",
                                                      {
                                                        attrs: { state: false },
                                                      },
                                                      [
                                                        _vm._v(
                                                          _vm._s(v.errors[0])
                                                        ),
                                                      ]
                                                    ),
                                                  ],
                                                  1
                                                ),
                                              ]
                                            },
                                          },
                                        ],
                                        null,
                                        true
                                      ),
                                    }),
                                    _vm._v(" "),
                                    _c("submit-button", {
                                      staticClass: "mt-2",
                                      attrs: {
                                        button_text: "Add Comment",
                                        submitted: _vm.submitted,
                                      },
                                    }),
                                  ],
                                  1
                                )
                              : _vm._e(),
                          ]
                        },
                      },
                    ]),
                  }),
                ],
                1
              ),
            ],
            1
          ),
        ]),
      ]),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "edit-comment-modal",
          attrs: {
            id: "edit-comment-modal",
            title: "Edit Comment",
            "hide-footer": "",
          },
        },
        [
          _c(
            "b-overlay",
            {
              attrs: { show: _vm.loading },
              scopedSlots: _vm._u([
                {
                  key: "overlay",
                  fn: function () {
                    return [_c("form-loader")]
                  },
                  proxy: true,
                },
              ]),
            },
            [
              _vm._v(" "),
              _c("ValidationObserver", {
                ref: "validator",
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
                                return handleSubmit(_vm.updateComment)
                              },
                            },
                          },
                          [
                            _c("ValidationProvider", {
                              attrs: { rules: "required" },
                              scopedSlots: _vm._u(
                                [
                                  {
                                    key: "default",
                                    fn: function (v) {
                                      return [
                                        _c(
                                          "b-form-group",
                                          [
                                            _c("b-form-textarea", {
                                              attrs: {
                                                rows: "3",
                                                "max-rows": "6",
                                              },
                                              model: {
                                                value: _vm.updateForm.comment,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.updateForm,
                                                    "comment",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "updateForm.comment",
                                              },
                                            }),
                                            _vm._v(" "),
                                            _c(
                                              "b-form-invalid-feedback",
                                              { attrs: { state: false } },
                                              [_vm._v(_vm._s(v.errors[0]))]
                                            ),
                                          ],
                                          1
                                        ),
                                      ]
                                    },
                                  },
                                ],
                                null,
                                true
                              ),
                            }),
                            _vm._v(" "),
                            _c("submit-button", {
                              staticClass: "mt-2",
                              attrs: {
                                button_text: "Update Comment",
                                submitted: _vm.loading,
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
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/manageTip.vue?vue&type=template&id=91b8e3c0&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/TechTips/manageTip.vue?vue&type=template&id=91b8e3c0& ***!
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
  return _c(
    "b-button",
    {
      directives: [
        {
          name: "b-tooltip",
          rawName: "v-b-tooltip.hover",
          modifiers: { hover: true },
        },
        {
          name: "b-modal",
          rawName: "v-b-modal.manage-tip-modal",
          modifiers: { "manage-tip-modal": true },
        },
      ],
      staticClass: "mt-1",
      attrs: {
        pill: "",
        block: "",
        variant: "danger",
        size: "sm",
        title: "Manage Tech Tip",
      },
    },
    [
      _c("i", { staticClass: "fas fa-tasks" }),
      _vm._v("\n    Manage\n    "),
      _c(
        "b-modal",
        {
          attrs: {
            id: "manage-tip-modal",
            title: "Manage Tech Tip",
            "hide-footer": "",
          },
          on: { show: _vm.getDetails },
        },
        [
          _c(
            "b-overlay",
            {
              attrs: { show: _vm.loading },
              scopedSlots: _vm._u([
                {
                  key: "overlay",
                  fn: function () {
                    return [_c("atom-loader")]
                  },
                  proxy: true,
                },
              ]),
            },
            [
              _vm._v(" "),
              _c("div", { staticClass: "row justify-content-center" }, [
                _vm.permissions.manage
                  ? _c(
                      "div",
                      { staticClass: "col-md-10" },
                      [
                        _c("h4", { staticClass: "text-center" }, [
                          _vm._v("Details"),
                        ]),
                        _vm._v(" "),
                        _c("b-table", {
                          attrs: { stacked: "", items: _vm.items },
                        }),
                      ],
                      1
                    )
                  : _vm._e(),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "row justify-content-center" }, [
                _vm.permissions.edit
                  ? _c(
                      "div",
                      { staticClass: "col-md-4" },
                      [
                        _c(
                          "inertia-link",
                          {
                            attrs: {
                              as: "b-button",
                              href: _vm.route("tech-tips.edit", _vm.tip_id),
                              block: "",
                              variant: "warning",
                            },
                          },
                          [_vm._v("Edit Tip")]
                        ),
                      ],
                      1
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm.permissions.delete
                  ? _c(
                      "div",
                      { staticClass: "col-md-4" },
                      [
                        _c(
                          "b-button",
                          {
                            attrs: { block: "", variant: "danger" },
                            on: { click: _vm.deleteTip },
                          },
                          [_vm._v("Delete Tip")]
                        ),
                      ],
                      1
                    )
                  : _vm._e(),
              ]),
            ]
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Show.vue?vue&type=template&id=7f2d77c5&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/TechTips/Show.vue?vue&type=template&id=7f2d77c5& ***!
  \********************************************************************************************************************************************************************************************************************/
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
    [
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-sm-10 grid-margin" }, [
          _c("h3", [
            _c("i", {
              directives: [
                {
                  name: "b-tooltip",
                  rawName: "v-b-tooltip.hover",
                  modifiers: { hover: true },
                },
              ],
              class: _vm.bookmark_class,
              attrs: { title: _vm.bookmark_title },
              on: { click: _vm.toggleFav },
            }),
            _vm._v(
              "\n                " + _vm._s(_vm.tip.subject) + "\n            "
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "tip-details" }, [
            _c("span", { staticClass: "d-block d-sm-inline-block" }, [
              _c("strong", [_vm._v("ID: ")]),
              _vm._v(_vm._s(_vm.tip.tip_id)),
            ]),
            _vm._v(" "),
            _c("span", { staticClass: "d-block d-sm-inline-block" }, [
              _c("strong", [_vm._v("Created: ")]),
              _vm._v(_vm._s(_vm.tip.created_at)),
            ]),
            _vm._v(" "),
            _c("span", { staticClass: "d-block d-sm-inline-block" }, [
              _c("strong", [_vm._v("Last Updated: ")]),
              _vm._v(_vm._s(_vm.tip.updated_at)),
            ]),
          ]),
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-sm-2" },
          [
            _c(
              "b-button",
              {
                directives: [
                  {
                    name: "b-tooltip",
                    rawName: "v-b-tooltip.hover",
                    modifiers: { hover: true },
                  },
                ],
                attrs: {
                  href: _vm.route("tips.download", _vm.tip.tip_id),
                  variant: "info",
                  size: "sm",
                  block: "",
                  pill: "",
                  title: "Download as PDF",
                },
              },
              [
                _c("i", { staticClass: "fas fa-download" }),
                _vm._v("\n                Download Tip\n            "),
              ]
            ),
            _vm._v(" "),
            _c("manage-tip", {
              attrs: {
                tip_id: _vm.tip.tip_id,
                permissions: _vm.user_data.permissions,
              },
            }),
          ],
          1
        ),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row mt-2 mt-md-0" }, [
        _c(
          "div",
          { staticClass: "col tip-equipment grid-margin" },
          [
            _vm._m(0),
            _vm._v(" "),
            _vm._l(_vm.tip.equipment_type, function (equip) {
              return _c(
                "b-badge",
                {
                  key: equip.equip_id,
                  staticClass: "ml-1 mb-1",
                  attrs: { pill: "", variant: "info" },
                },
                [_vm._v(_vm._s(equip.name))]
              )
            }),
          ],
          2
        ),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row grid-margin justify-content-center" }, [
        _c("div", { staticClass: "col" }, [
          _c("div", { staticClass: "card rounded" }, [
            _c("div", { staticClass: "card-body" }, [
              _c("div", { staticClass: "card-title" }, [_vm._v("Details:")]),
              _vm._v(" "),
              _c("div", {
                staticClass: "tip-body",
                domProps: { innerHTML: _vm._s(_vm.tip.details) },
              }),
            ]),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _vm.tip.file_uploads.length >= 1
        ? _c("div", { staticClass: "row grid-margin justify-content-center" }, [
            _c("div", { staticClass: "col" }, [
              _c("div", { staticClass: "card rounded" }, [
                _c("div", { staticClass: "card-body" }, [
                  _c("div", { staticClass: "card-title" }, [
                    _vm._v("Attachments:"),
                  ]),
                  _vm._v(" "),
                  _c(
                    "ul",
                    { staticClass: "list-group px-5" },
                    _vm._l(_vm.tip.file_uploads, function (file) {
                      return _c(
                        "li",
                        { key: file.file_id, staticClass: "list-group-item" },
                        [
                          _c(
                            "a",
                            {
                              attrs: {
                                href: _vm.route("download", [
                                  file.file_id,
                                  file.file_name,
                                ]),
                              },
                            },
                            [_vm._v(_vm._s(file.file_name))]
                          ),
                        ]
                      )
                    }),
                    0
                  ),
                ]),
              ]),
            ]),
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("discussion", {
        attrs: {
          comments: _vm.tip.tech_tip_comment,
          tip_id: _vm.tip.tip_id,
          permissions: _vm.user_data.permissions.comment,
        },
      }),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", [_c("strong", [_vm._v("For Equipment:")])])
  },
]
render._withStripped = true



/***/ })

}]);