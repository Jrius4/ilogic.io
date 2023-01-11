<template>
  <div>
    <input class="form-control" :type="type" :value="editValue" :name="fieldName" :id="fieldName"/>
  </div>
</template>

<script>

  export default {
    props:["fieldName","type","editValue"]
  }
</script>

<style scoped>

</style>
