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
          <tr v-for="(secret, i) in secrets" :key="i">
            <td>{{ secret.hash }}</td>
            <td>{{ secret.secret }}</td>
            <td>{{ secret.remainingViews }}</td>
            <td>{{ secret.createdAt }}</td>
            <td>{{ secret.expiresAt }}</td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'secretList',

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
