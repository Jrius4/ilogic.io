<template>
  <div id="app">
    <!-- <img alt="Vue logo" src="./assets/logo.png">
    <HelloWorld msg="Welcome to Your Vue.js App"/> -->
    <!-- <Reports/> -->
<editModeApi/>
    <!-- <form >
      <input required name />

      <button type="submit">save</button>
    </form> -->
  </div>
</template>

<script>
// import HelloWorld from './components/HelloWorld.vue';
import Reports from './components/Reports.vue';
import editModeApi from "./components/editModeApi.vue";

export default {
  name: 'App',
  components: {
    Reports,editModeApi
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* text-align: center; */
  color: #2c3e50;
  margin-top: 60px;
}
</style>
