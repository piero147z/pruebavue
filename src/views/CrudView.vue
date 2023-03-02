<template>
  <v-app id="app">
    <v-data-table :headers="headers" :items="libros" :search="id" class="elevation-3">
      <template v-slot:top>
          <v-system-bar color="indigo darken-2"></v-system-bar>
          <v-toolbar color="indigo">
            <v-btn class="mx-2" :elevation="10" fab dark color="teal accent-4" @click="dialog=true"><v-icon dark>
            mdi-plus</v-icon></v-btn>

          <v-divider class="mx-4" insert vertical></v-divider>
          <v-toolbar-title class="white--text">CRUD Libros</v-toolbar-title>  
          <v-spacer></v-spacer>  

          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{on}"></template>
            <v-card>
              <v-card-title class="cyan withe-text">
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editado.nombre" label="Nombre"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editado.autor" label="Autor"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editado.precio" type="number" step="1" min="0" label="Precio"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="blue-grey" class="ma-2 white--text" @click="cancelar">Cancelar</v-btn>
                <v-btn color="teal accent-4" class="ma-2 white--text" @click="guardar">Guardar</v-btn>
              </v-card-actions>

            </v-card>
          </v-dialog>

          </v-toolbar>

          <v-col cols="12" sm="12">
              <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
          </v-col>
        </template>

          <template v-slot:item.action="{item}">

            <v-btn class="mr-2" fab dark small color="cyan" @click="editar(item)">
            <v-icon dark>mdi-pencil</v-icon>
            </v-btn>
            <v-btn class="mr-2" fab dark small color="error" @click="borrar(item)">
            <v-icon dark>mdi-delete</v-icon>
            </v-btn>

          </template>

    </v-data-table>
    <template>
  <div class="text-center ma-2">
    <v-snackbar v-model="snackbar">
      {{ textSnack }}
      <v-btn color="info" text @click="snackbar = false">Cerrar</v-btn>
    </v-snackbar>
  </div>
</template>
</v-app>
</template>
<script>
  var url = "src/bd/crud.php";
  export default {
    el:"#app",
    data: () =>({
        search:'',
        snackbar:false,
        textSnack:'texto del snackbar',
        dialog:false,
        headers:[
          {
            text:'ID',
            aling: 'left',
            sorteable:false,
            value: 'id',
          },
          {text:'NOMBRE',value:'nombre'},
          {text:'AUTOR',value:'autor'},
          {text:'PRECIO',value:'precio'},
          {text:'ACCIONES',value:'accion', sorteable:false}
        ],
        libros:[],
        editedIndex:-1,
        editado:{
          id:'',
          nombre:'',
          autor:'',
          precio:''
        },
        defaultItem:{
          id:'',
          nombre:'',
          autor:'',
          precio:''
        }
    }),

    computed:{
      formTitle(){

        return this.editedIndex === -1 ? 'Nuevo Registro' : 'Editar Registro'
      }
    },
    watch:{
      dialog(val){
        val || this.cancelar()
      }
    },
    created(){
      this.listarLibros()
    },
    methods: {
      listarLibros:function () {
       this.axios.post(url,{opcion:4}).then(response => {
          this.libros = response.data;
        })
        
      },

      altaLibro:function () {
        this.axios.post(url,{opcion:1, nombre:this.nombre, autor:this.autor, precio:this.precio}).then(response =>{
          this.listarLibros();
        });
        this.nombre="",
        this.autor="",
        this.precio=0
        
      },
      editarLibro:function (id,nombre,autor,precio) {
        this.axios.post(url,{opcion:2, id:id, nombre:nombre, autor:autor, precio:precio}).then(response =>{
          this.listarLibros();
        })
      },
      borrarLibro:function (id) {
        this.axios.post(url,{opcion:3, id:id}).then(response =>{
          this.listarLibros();
        })
      },

      editar(item){
        this.editedIndex = this.libros.indexOf(item)
        this.editado = Object.assign({}, item)
        this.dialog = true
      },
      borrar(item){
        const index = this.libros.indexOf(item)

        var r = confirm("¿Quiere eliminar registro?");
        if (r == true) {
          this.borrarLibro(this.libros[index].id)
          this.snackbar = true
          this.textSnack = 'Se eliminó el registro'
        }else{
          this.snackbar = true
          this.textSnack = 'Operacion cancelada'
        }

      },
      cancelar(){
        this.dialog = false
        this.editado = Object.assign({},this.defaultItem)
        this.editedIndex = -1
      },
      guardar(){
        if (this.editedIndex > -1) {
          this.id = this.editado.id
          this.nombre = this.editado.nombre
          this.autor = this.editado.autor
          this.precio = this.editado.precio
          this.snackbar = true
          this.textSnack = 'Se actualizó'
          this.editarLibro(this.id,this.nombre, this.autor, this.precio)
        }else{
          if (this.editado.nombre == "" || this.editado.autor == "" || this.editado.precio == 0) {
            this.snackbar = true
            this.textSnack = 'Datos completos'
          }else{
          this.id = this.editado.id
          this.nombre = this.editado.nombre
          this.autor = this.editado.autor
          this.precio = this.editado.precio
          this.snackbar = true
          this.textSnack = 'Se actualizó'
          this.altaLibro()
        }
      }
        this.cancelar()
      }
    }
    }
</script>
