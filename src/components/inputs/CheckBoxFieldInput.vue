<template>
  <div class="row">
    <div class=" col-md-5" v-for="(option,key) in options">

      <label class="" :for="`${fieldName}_${option.value}_${key}`" :value="option.value"> <input :checked="getCheck(option.value)" class="checkbox m-1" type="checkbox" :name="fieldName" :id="`${fieldName}_${option.value}_${key}`"/> {{ option.name }} </label>
    </div>

  </div>
</template>

<script>

  export default {
    props:["fieldName","options","editValue"],

    methods: {
      getCheck(value) {
        let app = this;
        let res = app.$props.editValue;
        console.log({a:app.$props.editValue});
        res = res.map(elem=>{
          return `${elem}`.trim();
        })
        let resVal = res.filter(s=>s.includes(`${value}`.trim())).length>0?true:false;
       return resVal;
    },
  }}
</script>

<style scoped>



</style>
