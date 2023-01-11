<template>
  <div>
    <div class="d-flex">
      <select class="form-select form-control m-1 row" :name="fieldName" :id="`${fieldName}`">
        <option class="col-md-5" :selected="editValue.includes(option.value)"  v-for="(option,key) in options" :for="`${fieldName}_${option.value}`" :value="option.value"> {{ option.name }} </option>
      </select>
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
