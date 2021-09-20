<template>
    <tr>  
        <td>{{ secret.hash }}</td>
        <td>{{ secret.secret }}</td>
        <td>{{ secret.remainingViews }}</td>
        <td>{{ secret.createdAt }}</td>
        <td>{{ secret.expiresAt }}</td>
    </tr>
</template>

<script>
  export default {
    name: 'secretItem',

    props: {
        secret: {
            type: Object,
            default: function() {
                return { 
                    hash: 0,
                    secret: 0,
                    remainingViews: 0,
                    createdAt: 0,
                    expiresAt: 0
                };
            },
            required: true
        }
    }
  }
</script>