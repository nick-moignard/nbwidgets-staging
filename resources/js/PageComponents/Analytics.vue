<template>
  <div>
    <page-title :heading="heading" :subheading="subheading" icon="chart-bar"></page-title>
    <div class="mb-3 card">
      <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
          <i class="header-icon lnr-charts icon-gradient bg-happy-green"></i>
          Portfolio Performance
        </div>
        <div class="btn-actions-pane-right text-capitalize">
          <button class="btn-wide btn-outline-2x mr-md-2 btn btn-outline-focus btn-sm">View All</button>
        </div>
      </div>
      <div class="no-gutters row">
        <div class="col-sm-6 col-md-4 col-xl-4">
          <div class="card no-shadow rm-border bg-transparent widget-chart text-left">
            <div class="icon-wrapper rounded-circle">
              <div class="icon-wrapper-bg opacity-10 bg-warning"></div>
              <i class="lnr-laptop-phone text-dark opacity-8"></i>
            </div>
            <div class="widget-chart-content">
              <div class="widget-subheading">Cash Deposits</div>
              <div class="widget-numbers">1,7M</div>
              <div class="widget-description opacity-8 text-focus">
                <div class="d-inline text-danger pr-1">
                  <font-awesome-icon icon="angle-down" />
                  <span class="pl-1">54.1%</span>
                </div>less earnings
              </div>
            </div>
          </div>
          <div class="divider m-0 d-md-none d-sm-block"></div>
        </div>
        <div class="col-sm-6 col-md-4 col-xl-4">
          <div class="card no-shadow rm-border bg-transparent widget-chart text-left">
            <div class="icon-wrapper rounded-circle">
              <div class="icon-wrapper-bg opacity-9 bg-danger"></div>
              <i class="lnr-graduation-hat text-white"></i>
            </div>
            <div class="widget-chart-content">
              <div class="widget-subheading">Invested Dividents</div>
              <div class="widget-numbers">
                <span>9M</span>
              </div>
              <div class="widget-description opacity-8 text-focus">
                Grow Rate:
                <span class="text-info pl-1">
                  <font-awesome-icon icon="angle-down" />
                  <span class="pl-1">14.1%</span>
                </span>
              </div>
            </div>
          </div>
          <div class="divider m-0 d-md-none d-sm-block"></div>
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4">
          <div class="card no-shadow rm-border bg-transparent widget-chart text-left">
            <div class="icon-wrapper rounded-circle">
              <div class="icon-wrapper-bg opacity-9 bg-success"></div>
              <i class="lnr-apartment text-white"></i>
            </div>
            <div class="widget-chart-content">
              <div class="widget-subheading">Capital Gains</div>
              <div class="widget-numbers text-success">
                <span>$563</span>
              </div>
              <div class="widget-description text-focus">
                Increased by
                <span class="text-warning pl-1">
                  <font-awesome-icon icon="angle-up" />
                  <span class="pl-1">7.35%</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center d-block p-3 card-footer">
        <button class="btn-pill btn-shadow btn-wide fsize-1 btn btn-primary btn-lg">
          <span class="mr-2 opacity-7">
            <i class="icon icon-anim-pulse ion-ios-analytics-outline"></i>
          </span>
          <span class="mr-1">View Complete Report</span>
        </button>
      </div>
    </div>

    <div class="card mb-3">
      <div class="no-gutters row">
        <div class="col-md-12 col-lg-4">
          <ul class="list-group list-group-flush">
            <li class="bg-transparent list-group-item">
              <div class="widget-content p-0">
                <div class="widget-content-outer">
                  <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                      <div class="widget-heading">Total Orders</div>
                      <div class="widget-subheading">Last year expenses</div>
                    </div>
                    <div class="widget-content-right">
                      <div class="widget-numbers text-success">1896</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="bg-transparent list-group-item">
              <div class="widget-content p-0">
                <div class="widget-content-outer">
                  <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                      <div class="widget-heading">Clients</div>
                      <div class="widget-subheading">Total Clients Profit</div>
                    </div>
                    <div class="widget-content-right">
                      <div class="widget-numbers text-primary">$12.6k</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-12 col-lg-4">
          <ul class="list-group list-group-flush">
            <li class="bg-transparent list-group-item">
              <div class="widget-content p-0">
                <div class="widget-content-outer">
                  <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                      <div class="widget-heading">Followers</div>
                      <div class="widget-subheading">People Interested</div>
                    </div>
                    <div class="widget-content-right">
                      <div class="widget-numbers text-danger">45,9%</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="bg-transparent list-group-item">
              <div class="widget-content p-0">
                <div class="widget-content-outer">
                  <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                      <div class="widget-heading">Products Sold</div>
                      <div class="widget-subheading">Total revenue streams</div>
                    </div>
                    <div class="widget-content-right">
                      <div class="widget-numbers text-warning">$3M</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-12 col-lg-4">
          <ul class="list-group list-group-flush">
            <li class="bg-transparent list-group-item">
              <div class="widget-content p-0">
                <div class="widget-content-outer">
                  <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                      <div class="widget-heading">Total Orders</div>
                      <div class="widget-subheading">Last year expenses</div>
                    </div>
                    <div class="widget-content-right">
                      <div class="widget-numbers text-success">1896</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="bg-transparent list-group-item">
              <div class="widget-content p-0">
                <div class="widget-content-outer">
                  <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                      <div class="widget-heading">Clients</div>
                      <div class="widget-subheading">Total Clients Profit</div>
                    </div>
                    <div class="widget-content-right">
                      <div class="widget-numbers text-primary">$12.6k</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageTitle from "./PageTitle.vue";
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import Slick from "vue-slick";

import Trend from "vuetrend";

import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faTrashAlt,
  faCheck,
  faCalendarAlt,
  faAngleDown,
  faAngleUp,
  faTh
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faTrashAlt, faCheck, faAngleDown, faAngleUp, faTh, faCalendarAlt);

export default {
  components: {
    PageTitle,
    VuePerfectScrollbar,
    Slick,
    "font-awesome-icon": FontAwesomeIcon,
    trend: Trend,
  },
  data: () => ({
    heading: "Analytics Dashboard",
    subheading:
      "Nations Data",
    icon: "pe-7s-plane icon-gradient bg-tempting-azure",

    slickOptions2: {
      slidesToShow: 1,
      dots: true
    },

    fields: [
      {
        key: "last_name",
        sortable: true
      },
      {
        key: "first_name",
        sortable: true
      },
      {
        key: "age",
        label: "Person age",
        sortable: true
        // Variant applies to the whole column, including the header and footer
      }
    ],
    items: [
      {
        isActive: true,
        age: 40,
        first_name: "Dickerson",
        last_name: "Macdonald"
      },
      { isActive: false, age: 21, first_name: "Larsen", last_name: "Shaw" },
      { isActive: false, age: 89, first_name: "Geneva", last_name: "Wilson" },
      { isActive: true, age: 38, first_name: "Jami", last_name: "Carney" }
    ]
  }),

  methods: {},
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  mounted() {
    if (!this.currentUser) {
      this.$router.push("/login");
    }
  }
};
</script>
