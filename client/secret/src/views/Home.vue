<template>
  <div class="container mt-4">
    <div class="row">
      <h1>Secret List</h1>

      <hr />

      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Hash</th>
            <th scope="col">Secret</th>
            <th scope="col">Remaining views</th>
            <th scope="col">Created at</th>
            <th scope="col">Expires at</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="secret in secrets">
            <SecretItem :secret="secret" :key="`SecretItemId_${secret.hash}`" />
          </template>
        </tbody>
      </table>

    </div>
  </div>
</template>

<script>

import SecretItem from "@/components/SecretItem.vue";

export default {
    name: 'secretList',

    components: {
      SecretItem
    },

    data() {
      return {
        secrets: []
      }
    },

    async created() {
      let secrets = await this.$GetSecret('https://firstsymfonyproject.localhost/api/secret/list');

      this.secrets = secrets.data;
    }
  }
</script>
