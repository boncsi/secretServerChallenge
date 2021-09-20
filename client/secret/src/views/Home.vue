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
            <secretItem :secret="secret" :key="`SecretItemId_${secret.hash}`" />
          </template>
        </tbody>
      </table>

    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import secretItem from "@/components/secret/Item.vue";


  export default {
    name: 'secretList',

    components: {
      secretItem
    },

    data() {
      return {
        secrets: []
      }
    },

    created() {
      axios.get('https://firstsymfonyproject.localhost/api/secret/list', {
        auth: {
          username: 'boncsi',
          password: 'Almafa123!'
        }
      })
      .then(response => this.secrets = response.data)
      .catch(err => console.log(err))
    }
  }
</script>
