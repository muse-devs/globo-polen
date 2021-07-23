"use strict";

const batch_coupon = new Vue({
  el: "#batch-coupon",
  data: {
    prefix_name: "",
    amount: 0,
    discount_type: "percent",
    distount_type_list: [
      "fixed_cart",
      "percent",
      "fixed_product",
      "percent_product",
    ],
    description: "",
    expiry_date: "",
    usage_limit: "1"
  },
  methods: {
      createCoupon: function() {
          
      }
  }
});
