<template>
  <div class="row">
    <div class="d-flex col-md-5" v-for="(option,key) in options">
      <input class="radio m-1" :checked="editValue == option.value ?'checked':''" type="radio" :name="fieldName" :id="`${fieldName}_${option.value}`"/>
      <label :for="`${fieldName}_${option.value}`" :value="option.value">{{ option.name }}</label>
    </div>

  </div>
</template>

<script>

  export default {
    props:["fieldName","options","editValue"]
  }
</script>

<style scoped>



</style>
