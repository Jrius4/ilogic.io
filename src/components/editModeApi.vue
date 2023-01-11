<template>
  <div class="container">
    <h4>Edit Mode</h4>

    <form class="row" id="form-content">
      <div class="col-md-12">
        <div class="row justify-content-center" v-for="(cat,k) in formData" ::key="`${k}_cat`">
            <h3 class="text-muted text-center">{{ cat.name }}</h3>
           <div class="col-md-8 form-group my-4" v-for="(field,index) in cat.fields" :key="`${index}_field`">
              <label for="">{{ field.label }}</label>
              <div v-show="fieldTypeCheck(field) == 'text'">
                <TextFieldInput :type="'text'" :fieldName="field.field_input_name" :editValue="fieldValueEdit(field,fieldTypeCheck(field),field.field_input_name,cat.id,cat.alter_id)"/>
              </div>
              <div v-if="fieldTypeCheck(field) == 'number'">
                <TextFieldInput :type="'number'" :fieldName="field.field_input_name"  :editValue="fieldValueEdit(field,fieldTypeCheck(field),field.field_input_name,cat.id,cat.alter_id)"/>
              </div>

              <div v-else-if="fieldTypeCheck(field) == 'checkbox'">
                <CheckboxFieldInput :type="'checkbox'" :fieldName="field.field_input_name" :options="fieldOptions(field)"  :editValue="fieldValueEdit(field,fieldTypeCheck(field),field.field_input_name,cat.id,cat.alter_id)"/>
              </div>


              <div v-else-if="fieldTypeCheck(field) == 'radio'">
                <RadioFieldInput :fieldName="field.field_input_name" :options="fieldOptions(field)"  :editValue="fieldValueEdit(field,fieldTypeCheck(field),field.field_input_name,cat.id,cat.alter_id)"/>
              </div>
              <div v-else-if="fieldTypeCheck(field) == 'select'">
                <SelectFieldInput :fieldName="field.field_input_name" :options="fieldOptions(field)"  :editValue="fieldValueEdit(field,fieldTypeCheck(field),field.field_input_name,cat.id,cat.alter_id)"/>
              </div>
           </div>
        </div>

      </div>
      <div class="col-md-12">
       <button class="btn btn-primary d-block" type="submit" @click="(e)=>submitForm(e)">Submit</button>
      </div>
    </form>
  </div>
</template>

<script>
import member from "../assets/cooperative-profile.json"
import TextFieldInput from "./inputs/TextFieldInput.vue"
import RadioFieldInput from "./inputs/RadioFieldInput.vue"
import CheckboxFieldInput from "./inputs/CheckBoxFieldInput.vue"
import SelectFieldInput from "./inputs/SelectFieldInput.vue"
import axios from "axios"
  export default {
    name:"edit-mode-api",
    components:{
      TextFieldInput,RadioFieldInput,CheckboxFieldInput,SelectFieldInput
    },
    data() {
      return {
        step: 0,
        isLoading: false,
        form_stage: 1,
        formData: [],
        fieldData: [],
        editData:null,
        editDynamic:[]
      }
    },
    mounted() {
      let self = this;

      self.getInidivualData();

    },

    methods: {
      async getInidivualData(){
        let app = this;
        app.memberData = member;


        app.formData = app.memberData.formData
        app.editData = app.memberData.edit
        app.editDynamic = app.editData == null ? []: app.editData.dynamic
      },

    fieldTypeCheck(c) {
      if (c.field_type !== "null" && JSON.parse(c.field_type).type) {
        return JSON.parse(c.field_type).type;
      }

      return false;
    },
    fieldOptions(c){
      let options = [];
      if(c.field_options !== null){
        options =  JSON.parse(c.field_options)
      }

      console.log({options});

      return options;

    },
    fieldValueEdit(field,type,field_input_name,id,alter_id){
      console.log({field,type,field_input_name,id,alter_id});
        let app = this;
        let editFields = app.editDynamic.filter(s=>s.category_id == id || s.category_id == alter_id);

        let obj = editFields.length == 0 ? []: editFields[0].formData;


        var results = Object.fromEntries(Object.entries(obj).filter(([key]) => key.includes(field_input_name)));

        let resultValues = Object.values(results);
        console.log({editFields,resultValues});
        if(['text','number','select','percentage'].includes(type)){
          return `${resultValues[0]}`;
        }


        return resultValues;
    },
    submitForm(e){
      let form = $('form-content');
      e.preventDefault();

      let formData = new FormData(form[0]);

      axios.post('http://localhost:2016/api/someoutput',formData).then((res)=>{
        console.log({res});
      }).catch(err=>{
        console.log({err});
      })
    }
    },

  }
</script>

<style scoped>
::root{
  font-size: 3rem;
}
</style>
