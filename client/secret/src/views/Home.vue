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
          <SecretItem v-for="secret in secrets" :secret="secret" :key="`SecretItemId_${secret.hash}`" />
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
      let secrets = await this.$GetSecret();

      this.secrets = secrets.data;
    }
  }
</script>
