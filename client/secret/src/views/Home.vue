<template>
  <div class="home">
    <h1>Home</h1>
    <p>{{title}}</p>
  </div>
</template>

<script>
  import axios from 'vue-axios';

  export default {
    //Ha kívülről akarom elérni akkor itt tudom nevesíteni az exportal egybekötve tudom elérhetővé tenni kívülről.
    name: 'home',

    //Compneenseket itt tudok felvenni
    comments: {

    },

    //változók, amiket használhatok a templetemben
    //statikus adatok
    data() {
      return {
        title: 'Boncsi Home'
      }
    },

    //függvények, metódusok amik már figyelik a layouton a változáspokat, eseményeket
    //dinamikus adatok
    computed: {

    },

    //Hook a komponens létrehozásakor futtatható dolgok jönnek ide
    created() {

    },

    //Definiciók amiket a komponensünk akar használni
    methods: {

    }
  }
</script>