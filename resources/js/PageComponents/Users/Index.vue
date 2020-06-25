<template>
  <div>
    <page-title :heading="heading" :subheading="subheading" icon="search"></page-title>
    <b-card title="Users List" class="main-card mb-4">
      <b-row>
        <b-col md="6" class="my-1">
          <b-form-group horizontal label="Filter" class="mb-0">
            <b-input-group>
              <b-form-input v-model="filter" placeholder="Type to Search" />
              <b-input-group-append>
                <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col md="6" class="my-1">
          <b-form-group horizontal label="Sort" class="mb-0">
            <b-input-group>
              <b-form-select v-model="sortBy" :options="sortOptions">
                <option slot="first" :value="null">-- none --</option>
              </b-form-select>
              <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
                <option :value="false">Asc</option>
                <option :value="true">Desc</option>
              </b-form-select>
            </b-input-group>
          </b-form-group>
        </b-col>
      </b-row>

      <!-- Main table element -->
      <b-table
        show-empty
        stacked="md"
        :items="items"
        :fields="fields"
        :current-page="currentPage"
        :per-page="perPage"
        :filter="filter"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :sort-direction="sortDirection"
        @filtered="onFiltered"
      >
        <template v-slot:cell(actions)="row">
          <div class="row">
            <a :href="'/#/users/edit/' + row.item.id">
              <font-awesome-icon size="1x" icon="edit" style="color:green" />
            </a>
            <a @click="deleteUser(row.item.id)" class="ml-4 link" style="color:red">
              <font-awesome-icon size="1x" icon="trash" />
            </a>
          </div>
        </template>
      </b-table>

      <b-row>
        <b-col md="6" class="my-1">
          <b-pagination
            :total-rows="totalRows"
            :per-page="perPage"
            v-model="currentPage"
            class="my-0"
          />
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
import axios from "axios";
import PageTitle from "../PageTitle";
import swal from "sweetalert";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faEdit, faEye, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faEdit, faEye, faTrash);

const items = [];
export default {
  components: {
    PageTitle,
    "font-awesome-icon": FontAwesomeIcon
  },
  data: () => ({
    heading: "Users list",
    subheading: "All users are shown here",
    icon: "fa fa-search",

    items: items,
    fields: [
      { key: "id", label: "ID" },
      {
        key: "user_name",
        label: "Name",
        sortable: true,
        sortDirection: "desc"
      },
      { key: "email", label: "Email", sortable: true},
      { key: "role_name", label: "Role", sortable: true},
      { key: "actions", label: "Actions" }
    ],
    currentPage: 1,
    perPage: 100,
    totalRows: items.length,
    sortBy: null,
    sortDesc: false,
    sortDirection: "asc",
    filter: null,
    modalInfo: { title: "", content: "" }
  }),
  created() {
    axios
      .get(BASE_URL + "/api/users")
      .then(response => {
        if (response.status == 200) {
          this.items = response.data.data;
        }
      })
      .catch(error => {
        swal("Error", error, "error");
      });
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key };
        });
    },
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    info(item, index, button) {
      this.modalInfo.title = `Row index: ${index}`;
      this.modalInfo.content = JSON.stringify(item, null, 2);
      this.$root.$emit("bv::show::modal", "modalInfo", button);
    },
    resetModal() {
      this.modalInfo.title = "";
      this.modalInfo.content = "";
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    deleteUser(id) {
      swal({
        title: "Are you sure?",
        text: "Once deleted, user will not be able to access!",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          axios
            .delete(BASE_URL + "/api/users/" + id)
            .then(response => {
              if (response.status == 200) {
                swal("Success", "User deleted", "success");
                axios
                  .get(
                    BASE_URL +
                      "/api/users"
                  )
                  .then(response => {
                    if (response.status == 200) {
                      this.items = response.data.data;
                    }
                  })
                  .catch(error => {
                    swal("Error", error, "error");
                  });
              }
            })
            .catch(error => {
              swal("Error", error, "error");
            });
        }
      });
    }
  }
};
</script>
