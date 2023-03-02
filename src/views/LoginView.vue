<template>
  <v-container>
    <v-form ref="form" class="text-center">
      <h1>Login</h1><br>
    <v-text-field label="Usuario" :rules="rules" hide-details="auto"></v-text-field><br>
    <v-text-field label="Contraseña"></v-text-field>
    <v-btn class="ma-2" :loading="loading" :disabled="loading" color="secondary" @click="ir_crud()">
      Accept Terms
    </v-btn>
    </v-form>

  </v-container>
</template>



<script>
  export default {
    data () {
      return {
        loader: null,
        loading: false,
      }
    },

    methods:{
      ir_crud : function() {
        this.$router.push("/crud")
      },
    },
  }
</script>
