(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Customer_details_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    cust_id: {
      type: Number,
      required: true
    },
    data: {
      type: Array,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    equip_id: {
      type: Number,
      required: true
    },
    cust_equip_id: {
      type: Number,
      required: true
    }
  },
  data: function data() {
    return {
      loading: false,
      submitted: false,
      form: {
        cust_id: this.cust_id,
        equip_id: this.equip_id,
        shared: false,
        data: this.data
      }
    };
  },
  computed: {
    errors: function errors() {
      return this.$page.props.errors;
    }
  },
  methods: {
    openModal: function openModal() {
      this.$refs['edit-equipment-modal'].show();
    },
    submitForm: function submitForm() {
      var _this = this;

      this.submitted = true;
      this.loading = true;
      this.$inertia.put(route('customers.equipment.update', this.cust_equip_id), this.form, {
        onFinish: function onFinish() {
          _this.$refs['edit-equipment-modal'].hide();

          _this.loading = false;
          _this.submitted = false;

          _this.$emit('completed');
        }
      });
    },
    deleteEquip: function deleteEquip() {
      var _this2 = this;

      this.$bvModal.msgBoxConfirm('All information for this equipment will also be deleted', {
        title: 'Are you sure?',
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
          axios["delete"](_this2.route('customers.equipment.destroy', _this2.cust_equip_id)).then(function (res) {
            _this2.$refs['edit-equipment-modal'].hide();

            _this2.loading = false;
            _this2.submitted = false;

            _this2.$emit('completed');
          })["catch"](function (error) {
            return _this2.eventHub.$emit('axiosError', error);
          });
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    existing_equip: {
      type: Array,
      required: false
    },
    cust_id: {
      type: Number,
      required: true
    }
  },
  data: function data() {
    return {
      equipList: [],
      loading: true,
      submitted: false,
      form: {
        cust_id: this.cust_id,
        equip_id: null,
        shared: false,
        data: []
      }
    };
  },
  computed: {
    errors: function errors() {
      return this.$page.props.errors;
    }
  },
  methods: {
    getEquipList: function getEquipList() {
      var _this = this;

      if (this.equipList.length == 0) {
        axios.get(this.route('customers.equip-list')).then(function (res) {
          _this.equipList = res.data;
          _this.loading = false;
        })["catch"](function (error) {
          return _this.eventHub.$emit('axiosError', error);
        });
      }
    },
    populateForm: function populateForm(equip_id) {
      var _this2 = this;

      this.equipList.forEach(function (cat) {
        var equip = cat.equipment_type.filter(function (e) {
          return e.equip_id === equip_id;
        });

        if (equip.length == 1) {
          _this2.form.data = equip[0].data_field_type;
        }
      });
    },
    submitForm: function submitForm() {
      var _this3 = this;

      this.submitted = true;
      this.loading = true;
      this.$inertia.post(route('customers.equipment.store'), this.form, {
        onFinish: function onFinish() {
          _this3.$refs['new-equipment-modal'].hide();

          _this3.$emit('completed');
        }
      });
    },
    resetForm: function resetForm() {
      this.form = {
        equip_id: null,
        shared: false,
        data: []
      };
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/customerEquipment.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/customerEquipment.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Equipment_editEquipmentModal_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Equipment/editEquipmentModal.vue */ "./resources/js/Components/Customer/Equipment/editEquipmentModal.vue");
/* harmony import */ var _Equipment_newEquipmentModal_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Equipment/newEquipmentModal.vue */ "./resources/js/Components/Customer/Equipment/newEquipmentModal.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    newEquipmentModal: _Equipment_newEquipmentModal_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    EditEquipmentModal: _Equipment_editEquipmentModal_vue__WEBPACK_IMPORTED_MODULE_0__.default
  },
  props: {
    customer_equipment: {
      type: Array,
      required: true
    },
    cust_id: {
      type: Number,
      required: true
    }
  },
  data: function data() {
    return {
      equipment: this.customer_equipment,
      loading: false
    };
  },
  computed: {
    equipIdList: function equipIdList() {
      var list = [];
      this.equipment.forEach(function (item) {
        list.push(item.equip_id);
      });
      return list;
    }
  },
  methods: {
    getEquipData: function getEquipData(data) {
      var dataList = [];
      data.forEach(function (el) {
        dataList[el.field_name] = el.value;
      });
      return [dataList];
    },
    getEquipment: function getEquipment() {
      var _this = this;

      this.loading = true;
      axios.get(this.route('customers.equipment.show', this.cust_id)).then(function (res) {
        _this.equipment = res.data;
        _this.loading = false;
      })["catch"](function (error) {
        return _this.eventHub.$emit('axiosError', error);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/editDetails.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/editDetails.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    details: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      submitted: false,
      form: {
        name: this.details.name,
        dba_name: this.details.dba_name,
        address: this.details.address,
        city: this.details.city,
        state: this.details.state,
        zip: this.details.zip
      }
    };
  },
  methods: {
    submitForm: function submitForm() {
      var _this = this;

      this.submitted = true;
      this.$inertia.put(route('customers.update', this.details.cust_id), this.form, {
        onFinish: function onFinish() {
          _this.submitted = false;

          _this.$refs['edit-customer-modal'].hide();
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

"use strict";
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {//
  },
  data: function data() {
    return {
      //
      showNav: false
    };
  },
  created: function created() {//
  },
  mounted: function mounted() {//
  },
  computed: {
    app: function app() {
      return this.$page.props.app;
    },
    user: function user() {
      return this.$page.props.user;
    },
    navbarActive: function navbarActive() {
      return this.showNav ? 'active' : '';
    },
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Customer/details.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Customer/details.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Layouts_app__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../Layouts/app */ "./resources/js/Layouts/app.vue");
/* harmony import */ var _Components_Customer_editDetails_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Components/Customer/editDetails.vue */ "./resources/js/Components/Customer/editDetails.vue");
/* harmony import */ var _Components_Customer_customerEquipment_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Components/Customer/customerEquipment.vue */ "./resources/js/Components/Customer/customerEquipment.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    editDetails: _Components_Customer_editDetails_vue__WEBPACK_IMPORTED_MODULE_1__.default,
    CustomerEquipment: _Components_Customer_customerEquipment_vue__WEBPACK_IMPORTED_MODULE_2__.default
  },
  layout: _Layouts_app__WEBPACK_IMPORTED_MODULE_0__.default,
  props: {
    details: {
      type: Object,
      required: true
    },
    user_functions: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      is_fav: this.user_functions.fav
    };
  },
  created: function created() {//
  },
  mounted: function mounted() {//
  },
  computed: {
    map_url: function map_url() {
      return 'https://maps.google.com/?q=' + encodeURI(this.details.address + ',' + this.details.city + ',' + this.details.state);
    },
    bookmark_class: function bookmark_class() {
      return this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
    },
    bookmark_title: function bookmark_title() {
      return this.is_fav ? 'Remove From Bookmarks' : 'Add to Bookmarks';
    }
  },
  watch: {//
  },
  methods: {
    toggleFav: function toggleFav() {
      axios.put(this.route('customers.bookmark'), {
        cust_id: this.details.cust_id,
        state: !this.is_fav
      }).then(this.is_fav = !this.is_fav);
    }
  }
});

/***/ }),

/***/ "./resources/js/Components/Customer/Equipment/editEquipmentModal.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/Components/Customer/Equipment/editEquipmentModal.vue ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _editEquipmentModal_vue_vue_type_template_id_e3dcf700___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./editEquipmentModal.vue?vue&type=template&id=e3dcf700& */ "./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=template&id=e3dcf700&");
/* harmony import */ var _editEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./editEquipmentModal.vue?vue&type=script&lang=js& */ "./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _editEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _editEquipmentModal_vue_vue_type_template_id_e3dcf700___WEBPACK_IMPORTED_MODULE_0__.render,
  _editEquipmentModal_vue_vue_type_template_id_e3dcf700___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Components/Customer/Equipment/editEquipmentModal.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Components/Customer/Equipment/newEquipmentModal.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/Components/Customer/Equipment/newEquipmentModal.vue ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _newEquipmentModal_vue_vue_type_template_id_23610ab8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./newEquipmentModal.vue?vue&type=template&id=23610ab8& */ "./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=template&id=23610ab8&");
/* harmony import */ var _newEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./newEquipmentModal.vue?vue&type=script&lang=js& */ "./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _newEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _newEquipmentModal_vue_vue_type_template_id_23610ab8___WEBPACK_IMPORTED_MODULE_0__.render,
  _newEquipmentModal_vue_vue_type_template_id_23610ab8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Components/Customer/Equipment/newEquipmentModal.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Components/Customer/customerEquipment.vue":
/*!****************************************************************!*\
  !*** ./resources/js/Components/Customer/customerEquipment.vue ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _customerEquipment_vue_vue_type_template_id_b9c8f32c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./customerEquipment.vue?vue&type=template&id=b9c8f32c& */ "./resources/js/Components/Customer/customerEquipment.vue?vue&type=template&id=b9c8f32c&");
/* harmony import */ var _customerEquipment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./customerEquipment.vue?vue&type=script&lang=js& */ "./resources/js/Components/Customer/customerEquipment.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _customerEquipment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _customerEquipment_vue_vue_type_template_id_b9c8f32c___WEBPACK_IMPORTED_MODULE_0__.render,
  _customerEquipment_vue_vue_type_template_id_b9c8f32c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Components/Customer/customerEquipment.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Components/Customer/editDetails.vue":
/*!**********************************************************!*\
  !*** ./resources/js/Components/Customer/editDetails.vue ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _editDetails_vue_vue_type_template_id_2abfad1c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./editDetails.vue?vue&type=template&id=2abfad1c& */ "./resources/js/Components/Customer/editDetails.vue?vue&type=template&id=2abfad1c&");
/* harmony import */ var _editDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./editDetails.vue?vue&type=script&lang=js& */ "./resources/js/Components/Customer/editDetails.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _editDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _editDetails_vue_vue_type_template_id_2abfad1c___WEBPACK_IMPORTED_MODULE_0__.render,
  _editDetails_vue_vue_type_template_id_2abfad1c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Components/Customer/editDetails.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Layouts/app.vue":
/*!**************************************!*\
  !*** ./resources/js/Layouts/app.vue ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

/***/ "./resources/js/Pages/Customer/details.vue":
/*!*************************************************!*\
  !*** ./resources/js/Pages/Customer/details.vue ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _details_vue_vue_type_template_id_0301263a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./details.vue?vue&type=template&id=0301263a& */ "./resources/js/Pages/Customer/details.vue?vue&type=template&id=0301263a&");
/* harmony import */ var _details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./details.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Customer/details.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _details_vue_vue_type_template_id_0301263a___WEBPACK_IMPORTED_MODULE_0__.render,
  _details_vue_vue_type_template_id_0301263a___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Customer/details.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_editEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./editEquipmentModal.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_editEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_newEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./newEquipmentModal.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_newEquipmentModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Components/Customer/customerEquipment.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/Components/Customer/customerEquipment.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_customerEquipment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./customerEquipment.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/customerEquipment.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_customerEquipment_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Components/Customer/editDetails.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/Components/Customer/editDetails.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_editDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./editDetails.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/editDetails.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_editDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Layouts/app.vue?vue&type=script&lang=js&":
/*!***************************************************************!*\
  !*** ./resources/js/Layouts/app.vue?vue&type=script&lang=js& ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./app.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Pages/Customer/details.vue?vue&type=script&lang=js&":
/*!**************************************************************************!*\
  !*** ./resources/js/Pages/Customer/details.vue?vue&type=script&lang=js& ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./details.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Customer/details.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=template&id=e3dcf700&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=template&id=e3dcf700& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_editEquipmentModal_vue_vue_type_template_id_e3dcf700___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_editEquipmentModal_vue_vue_type_template_id_e3dcf700___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_editEquipmentModal_vue_vue_type_template_id_e3dcf700___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./editEquipmentModal.vue?vue&type=template&id=e3dcf700& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=template&id=e3dcf700&");


/***/ }),

/***/ "./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=template&id=23610ab8&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=template&id=23610ab8& ***!
  \*********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_newEquipmentModal_vue_vue_type_template_id_23610ab8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_newEquipmentModal_vue_vue_type_template_id_23610ab8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_newEquipmentModal_vue_vue_type_template_id_23610ab8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./newEquipmentModal.vue?vue&type=template&id=23610ab8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=template&id=23610ab8&");


/***/ }),

/***/ "./resources/js/Components/Customer/customerEquipment.vue?vue&type=template&id=b9c8f32c&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/Components/Customer/customerEquipment.vue?vue&type=template&id=b9c8f32c& ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_customerEquipment_vue_vue_type_template_id_b9c8f32c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_customerEquipment_vue_vue_type_template_id_b9c8f32c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_customerEquipment_vue_vue_type_template_id_b9c8f32c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./customerEquipment.vue?vue&type=template&id=b9c8f32c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/customerEquipment.vue?vue&type=template&id=b9c8f32c&");


/***/ }),

/***/ "./resources/js/Components/Customer/editDetails.vue?vue&type=template&id=2abfad1c&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/Components/Customer/editDetails.vue?vue&type=template&id=2abfad1c& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_editDetails_vue_vue_type_template_id_2abfad1c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_editDetails_vue_vue_type_template_id_2abfad1c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_editDetails_vue_vue_type_template_id_2abfad1c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./editDetails.vue?vue&type=template&id=2abfad1c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/editDetails.vue?vue&type=template&id=2abfad1c&");


/***/ }),

/***/ "./resources/js/Layouts/app.vue?vue&type=template&id=191620ed&":
/*!*********************************************************************!*\
  !*** ./resources/js/Layouts/app.vue?vue&type=template&id=191620ed& ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_app_vue_vue_type_template_id_191620ed___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./app.vue?vue&type=template&id=191620ed& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Layouts/app.vue?vue&type=template&id=191620ed&");


/***/ }),

/***/ "./resources/js/Pages/Customer/details.vue?vue&type=template&id=0301263a&":
/*!********************************************************************************!*\
  !*** ./resources/js/Pages/Customer/details.vue?vue&type=template&id=0301263a& ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_template_id_0301263a___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_template_id_0301263a___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_details_vue_vue_type_template_id_0301263a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./details.vue?vue&type=template&id=0301263a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Customer/details.vue?vue&type=template&id=0301263a&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=template&id=e3dcf700&":
/*!*************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/editEquipmentModal.vue?vue&type=template&id=e3dcf700& ***!
  \*************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
    [
      _c(
        "b-button",
        {
          attrs: { pill: "", variant: "warning", size: "sm" },
          on: { click: _vm.openModal }
        },
        [
          _c("i", { staticClass: "fas fa-pencil-alt" }),
          _vm._v("\n        Edit\n        "),
          _c(
            "b-modal",
            {
              ref: "edit-equipment-modal",
              attrs: { title: "Edit Equipment", "hide-footer": "" }
            },
            [
              _c(
                "b-overlay",
                { attrs: { show: _vm.loading } },
                [
                  _c("ValidationObserver", {
                    scopedSlots: _vm._u([
                      {
                        key: "default",
                        fn: function(ref) {
                          var handleSubmit = ref.handleSubmit
                          return [
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
                                _c("h4", { staticClass: "text-center" }, [
                                  _vm._v(_vm._s(_vm.name))
                                ]),
                                _vm._v(" "),
                                _c(
                                  "b-form-checkbox",
                                  {
                                    staticClass: "text-center",
                                    attrs: { switch: "" },
                                    model: {
                                      value: _vm.form.shared,
                                      callback: function($$v) {
                                        _vm.$set(_vm.form, "shared", $$v)
                                      },
                                      expression: "form.shared"
                                    }
                                  },
                                  [_vm._v("Share Equipment Across All Sites")]
                                ),
                                _vm._v(" "),
                                _vm._l(_vm.form.data, function(d, index) {
                                  return _c("text-input", {
                                    key: index,
                                    attrs: { label: d.field_name },
                                    model: {
                                      value: _vm.form.data[index].value,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.form.data[index],
                                          "value",
                                          $$v
                                        )
                                      },
                                      expression: "form.data[index].value"
                                    }
                                  })
                                }),
                                _vm._v(" "),
                                _c("submit-button", {
                                  attrs: {
                                    button_text: "Update Equipment",
                                    submitted: _vm.submitted
                                  }
                                })
                              ],
                              2
                            ),
                            _vm._v(" "),
                            _c(
                              "b-button",
                              {
                                staticClass: "mt-4",
                                attrs: { variant: "danger", block: "" },
                                on: { click: _vm.deleteEquip }
                              },
                              [_vm._v("Delete Equipment")]
                            )
                          ]
                        }
                      }
                    ])
                  })
                ],
                1
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
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=template&id=23610ab8&":
/*!************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/Equipment/newEquipmentModal.vue?vue&type=template&id=23610ab8& ***!
  \************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
    [
      _c(
        "b-button",
        {
          directives: [
            {
              name: "b-modal",
              rawName: "v-b-modal.new-equipment-modal",
              modifiers: { "new-equipment-modal": true }
            }
          ],
          staticClass: "float-right",
          attrs: { pill: "", variant: "primary", size: "sm" }
        },
        [
          _c("i", { staticClass: "fas fa-plus" }),
          _vm._v("\n        New\n        "),
          _c(
            "b-modal",
            {
              ref: "new-equipment-modal",
              attrs: {
                id: "new-equipment-modal",
                title: "Add New Equipment",
                "hide-footer": ""
              },
              on: { show: _vm.getEquipList, hidden: _vm.resetForm }
            },
            [
              _c(
                "b-overlay",
                { attrs: { show: _vm.loading } },
                [
                  _c("ValidationObserver", {
                    scopedSlots: _vm._u([
                      {
                        key: "default",
                        fn: function(ref) {
                          var handleSubmit = ref.handleSubmit
                          return [
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
                                _c(
                                  "dropdown-input",
                                  {
                                    attrs: {
                                      rules: "required",
                                      label: "Select Equipment Type"
                                    },
                                    on: { change: _vm.populateForm },
                                    model: {
                                      value: _vm.form.equip_id,
                                      callback: function($$v) {
                                        _vm.$set(_vm.form, "equip_id", $$v)
                                      },
                                      expression: "form.equip_id"
                                    }
                                  },
                                  [
                                    _c(
                                      "option",
                                      { domProps: { value: null } },
                                      [_vm._v("Select An Equipment Type")]
                                    ),
                                    _vm._v(" "),
                                    _vm._l(_vm.equipList, function(cat) {
                                      return _c(
                                        "optgroup",
                                        {
                                          key: cat.cat_id,
                                          attrs: { label: cat.name }
                                        },
                                        _vm._l(cat.equipment_type, function(
                                          equip
                                        ) {
                                          return _c(
                                            "option",
                                            {
                                              key: equip.equip_id,
                                              attrs: {
                                                disabled: _vm.existing_equip.includes(
                                                  equip.equip_id
                                                )
                                              },
                                              domProps: {
                                                value: equip.equip_id
                                              }
                                            },
                                            [_vm._v(_vm._s(equip.name))]
                                          )
                                        }),
                                        0
                                      )
                                    })
                                  ],
                                  2
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-form-checkbox",
                                  {
                                    staticClass: "text-center",
                                    attrs: { switch: "" },
                                    model: {
                                      value: _vm.form.shared,
                                      callback: function($$v) {
                                        _vm.$set(_vm.form, "shared", $$v)
                                      },
                                      expression: "form.shared"
                                    }
                                  },
                                  [_vm._v("Share Equipment Across All Sites")]
                                ),
                                _vm._v(" "),
                                _vm._l(_vm.form.data, function(d, index) {
                                  return _c("text-input", {
                                    key: index,
                                    attrs: { label: d.name },
                                    model: {
                                      value: _vm.form.data[index].value,
                                      callback: function($$v) {
                                        _vm.$set(
                                          _vm.form.data[index],
                                          "value",
                                          $$v
                                        )
                                      },
                                      expression: "form.data[index].value"
                                    }
                                  })
                                }),
                                _vm._v(" "),
                                _c("submit-button", {
                                  attrs: {
                                    button_text: "Add Equipment",
                                    submitted: _vm.submitted
                                  }
                                })
                              ],
                              2
                            )
                          ]
                        }
                      }
                    ])
                  })
                ],
                1
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
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/customerEquipment.vue?vue&type=template&id=b9c8f32c&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/customerEquipment.vue?vue&type=template&id=b9c8f32c& ***!
  \**************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
    [
      _c(
        "div",
        { staticClass: "card-title" },
        [
          _vm._v("\n        Equipment:\n        "),
          _c("new-equipment-modal", {
            attrs: { existing_equip: _vm.equipIdList, cust_id: _vm.cust_id },
            on: { completed: _vm.getEquipment }
          })
        ],
        1
      ),
      _vm._v(" "),
      _c("b-overlay", { attrs: { show: _vm.loading } }, [
        _vm.equipment.length > 0
          ? _c(
              "div",
              _vm._l(_vm.equipment, function(equip, index) {
                return _c(
                  "div",
                  { key: index },
                  [
                    _c(
                      "b-card-header",
                      [
                        _c(
                          "b-button",
                          {
                            directives: [
                              {
                                name: "b-toggle",
                                rawName: "v-b-toggle",
                                value: "equip-" + index,
                                expression: "'equip-'+index"
                              }
                            ],
                            attrs: { block: "", variant: "info" }
                          },
                          [_vm._v(_vm._s(equip.name))]
                        )
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "b-collapse",
                      {
                        attrs: {
                          id: "equip-" + index,
                          accordion: "equipment-accordion",
                          visible: index === 0 ? true : false
                        }
                      },
                      [
                        _c(
                          "b-card-body",
                          [
                            _c("b-table", {
                              attrs: {
                                stacked: "",
                                small: "",
                                items: _vm.getEquipData(
                                  equip.customer_equipment_data
                                )
                              }
                            }),
                            _vm._v(" "),
                            _c(
                              "div",
                              { staticClass: "text-center" },
                              [
                                _c("edit-equipment-modal", {
                                  attrs: {
                                    cust_id: _vm.cust_id,
                                    data: equip.customer_equipment_data,
                                    name: equip.name,
                                    equip_id: equip.equip_id,
                                    cust_equip_id: equip.cust_equip_id
                                  },
                                  on: { completed: _vm.getEquipment }
                                })
                              ],
                              1
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
              }),
              0
            )
          : _c("div", [
              _c("h5", { staticClass: "text-center" }, [
                _vm._v("No Equipment Has Been Assigned")
              ])
            ])
      ])
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/editDetails.vue?vue&type=template&id=2abfad1c&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Components/Customer/editDetails.vue?vue&type=template&id=2abfad1c& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
    [
      _c(
        "b-button",
        {
          directives: [
            {
              name: "b-tooltip",
              rawName: "v-b-tooltip.hover",
              modifiers: { hover: true }
            },
            {
              name: "b-modal",
              rawName: "v-b-modal.edit-customer-modal",
              modifiers: { "edit-customer-modal": true }
            }
          ],
          attrs: {
            variant: "warning",
            block: "",
            pill: "",
            size: "sm",
            title: "Edit Customer Details"
          }
        },
        [
          _c("i", { staticClass: "fas fa-pencil-alt" }),
          _vm._v("\n        Edit\n    ")
        ]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "edit-customer-modal",
          attrs: {
            title: "Edit Customer Details",
            id: "edit-customer-modal",
            "hide-footer": ""
          }
        },
        [
          _c(
            "b-overlay",
            { attrs: { show: _vm.submitted } },
            [
              _c("ValidationObserver", {
                scopedSlots: _vm._u([
                  {
                    key: "default",
                    fn: function(ref) {
                      var handleSubmit = ref.handleSubmit
                      return [
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
                                label: "Customer Name",
                                name: "name",
                                placeholder: "Enter Customer Name",
                                rules: "required"
                              },
                              model: {
                                value: _vm.form.name,
                                callback: function($$v) {
                                  _vm.$set(_vm.form, "name", $$v)
                                },
                                expression: "form.name"
                              }
                            }),
                            _vm._v(" "),
                            _c("text-input", {
                              attrs: {
                                label: "DBA Name",
                                name: "dba_name",
                                placeholder: "Customer secondary name/AKA name"
                              },
                              model: {
                                value: _vm.form.dba_name,
                                callback: function($$v) {
                                  _vm.$set(_vm.form, "dba_name", $$v)
                                },
                                expression: "form.dba_name"
                              }
                            }),
                            _vm._v(" "),
                            _c("text-input", {
                              attrs: {
                                label: "Customer Address",
                                name: "address",
                                rules: "required"
                              },
                              model: {
                                value: _vm.form.address,
                                callback: function($$v) {
                                  _vm.$set(_vm.form, "address", $$v)
                                },
                                expression: "form.address"
                              }
                            }),
                            _vm._v(" "),
                            _c("text-input", {
                              attrs: {
                                label: "City",
                                name: "city",
                                rules: "required"
                              },
                              model: {
                                value: _vm.form.city,
                                callback: function($$v) {
                                  _vm.$set(_vm.form, "city", $$v)
                                },
                                expression: "form.city"
                              }
                            }),
                            _vm._v(" "),
                            _c(
                              "b-form-row",
                              [
                                _c(
                                  "b-col",
                                  { attrs: { md: "6" } },
                                  [
                                    _c("all-states", {
                                      model: {
                                        value: _vm.form.state,
                                        callback: function($$v) {
                                          _vm.$set(_vm.form, "state", $$v)
                                        },
                                        expression: "form.state"
                                      }
                                    })
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-col",
                                  { attrs: { md: "6" } },
                                  [
                                    _c("text-input", {
                                      attrs: {
                                        label: "Zip Code",
                                        name: "zip",
                                        rules: "required|numeric"
                                      },
                                      model: {
                                        value: _vm.form.zip,
                                        callback: function($$v) {
                                          _vm.$set(_vm.form, "zip", $$v)
                                        },
                                        expression: "form.zip"
                                      }
                                    })
                                  ],
                                  1
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c("submit-button", {
                              attrs: {
                                button_text: "Update Customer Details",
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
        ],
        1
      )
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

"use strict";
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
                        attrs: { variant: "warning", text: _vm.user.initials }
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
                    href: _vm.route("password.edit", "change")
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
      _c("div", { staticClass: "content" }, [
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
            _vm._t("default")
          ],
          2
        ),
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
      ])
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Customer/details.vue?vue&type=template&id=0301263a&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/Pages/Customer/details.vue?vue&type=template&id=0301263a& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-8 grid-margin stretch-card" }, [
        _c("h3", [
          _c("i", {
            directives: [
              {
                name: "b-tooltip",
                rawName: "v-b-tooltip.hover",
                modifiers: { hover: true }
              }
            ],
            class: _vm.bookmark_class,
            attrs: { title: _vm.bookmark_title },
            on: { click: _vm.toggleFav }
          }),
          _vm._v(
            "\n                " + _vm._s(_vm.details.name) + "\n            "
          )
        ]),
        _vm._v(" "),
        _vm.details.dba_name
          ? _c("h5", [_vm._v("AKA - " + _vm._s(_vm.details.dba_name))])
          : _vm._e(),
        _vm._v(" "),
        _vm.details.parent_id
          ? _c(
              "h5",
              [
                _vm._v("Child Site of - "),
                _c(
                  "inertia-link",
                  {
                    attrs: {
                      href: _vm.route("customers.show", _vm.details.parent.slug)
                    }
                  },
                  [_vm._v(_vm._s(_vm.details.parent.name))]
                )
              ],
              1
            )
          : _vm._e(),
        _vm._v(" "),
        _c("address", [
          _vm._m(0),
          _vm._v(" "),
          _c(
            "a",
            {
              directives: [
                {
                  name: "b-tooltip",
                  rawName: "v-b-tooltip.hover",
                  modifiers: { hover: true }
                }
              ],
              staticClass: "float-left ml-2",
              attrs: {
                href: _vm.map_url,
                target: "_blank",
                id: "addr-span",
                title: "Click for Google Maps"
              }
            },
            [
              _vm._v("\n                    " + _vm._s(_vm.details.address)),
              _c("br"),
              _vm._v(
                "\n                    " +
                  _vm._s(_vm.details.city) +
                  ", " +
                  _vm._s(_vm.details.state) +
                  "  " +
                  _vm._s(_vm.details.zip) +
                  "\n                "
              )
            ]
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-4 mt-md-0 mt-4" }, [
        _c(
          "div",
          { staticClass: "float-md-right" },
          [
            _vm.user_functions.edit
              ? _c("edit-details", { attrs: { details: _vm.details } })
              : _vm._e()
          ],
          1
        )
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-md-6 grid-margin stretch-card" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c("customer-equipment", {
                attrs: {
                  customer_equipment: _vm.details.customer_equipment,
                  cust_id: _vm.details.cust_id
                }
              })
            ],
            1
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-md-6 grid-margin stretch-card" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-body" },
            [
              _c("div", { staticClass: "card-title" }, [_vm._v("Contacts:")]),
              _vm._v(" "),
              _c(
                "b-list-group",
                [_c("b-list-group-item", [_vm._v("Something")])],
                1
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
    return _c("div", { staticClass: "float-left" }, [
      _c("i", { staticClass: "fas fa-map-marked-alt text-muted" })
    ])
  }
]
render._withStripped = true



/***/ })

}]);