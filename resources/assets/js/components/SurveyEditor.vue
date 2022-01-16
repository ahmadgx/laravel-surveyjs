<template>
    <div>
        <v-toolbar>
            <v-btn icon class="mb-3" @click.native = "$router.push({name: 'home'})">
                <v-icon large>home</v-icon>
            </v-btn>
            <v-toolbar-title @click.prevent="nameField = true" v-if="!nameField">{{surveyName}} / Branch: {{branch_name}}</v-toolbar-title>

            <v-flex xs4 v-else>
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="surveyName"
                        :rules="[
                                            () => !!surveyName || 'The field name is required',
                                            () => !!surveyName && surveyName.length >= 3 || 'Name must contain at least 3 character!',
                                            ]"
                ></v-text-field>

                 <!--branch list-->
                <div class="form-group right">
                    <label>Branch:</label>
                    <select class='form-control' v-model='branch_id' name="branch_id"
                            :rules="[
                                            () => !!branch_id || 'The field branch is required',
                                            ]">
                        <option value='0' >Select Branch</option>
                        <option v-for='data in branches' :value='data.id'>{{ data.name }}</option>
                    </select>
                </div>
                <!-- end branch list-->
            </v-flex>


            <v-toolbar-items v-if="nameField">
                <v-btn small flat color="primary" @click.prevent="postEdit">Save</v-btn>
                <v-btn small flat color="warning" @click.prevent="onCancelEdit">Cancel</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <br>
        <a :href="surveySlug" target="_blank"> {{surveySlug}}</a>



        <survey-builder :json="survey.json" :id="survey.id" v-if="Object.keys(survey).length"></survey-builder>


    </div>
</template>

<script>
    import SurveyBuilder from './../components/SurveyBuilder'

    export default {
        components: {
            SurveyBuilder
        },
        data () {
            return {
                survey: {},
                surveyName: '',
                surveySlug: '',
                nameField: false,
                //branch list
                branch_id: 0,
                branch_name: '',
                branches: [],

            }
        },
        mounted() {
            this.getSurvey(this.$route.params.id);
        },
        methods: {
            getSurvey(id) {
                axios.get('/form/' + id)
                    .then((response) => {
                        this.survey = response.data.data;
                        this.surveyName = response.data.data.name;
                        this.branch_id = response.data.data.branch_id;
                        this.branch_name = response.data.data.branch_name;
                        //this.surveySlug = this.$router.toPath('/' + SurveyConfig.route_prefix + '/' + response.data.data.slug);
                        var path_show = new URL(location.href);
                        this.surveySlug = path_show.origin + '/' + SurveyConfig.route_prefix + '/' + response.data.data.slug;
                    })
                    .catch((error) => {
                        console.log(error.response)
                    })
            },
            onCancelEdit() {
                this.nameField = false;
                this.surveyName = this.survey.name;
            },
            postEdit() {
                axios.put('/form/' + this.survey.id, {name: this.surveyName,branch_id: this.branch_id})
                    .then((response) => {
                        this.nameField = false;
                        this.$root.snackbarMsg = response.data.message;
                        this.$root.snackbar = true;
                        this.branch_name = response.data.branch_name;
                    })
                    .catch((error) => {
                        console.error(error.response);
                    })
            },
            getBranches: function(){

                axios.get('/get-branches')

                    .then(function (response) {

                        this.branches = response.data;

                    }.bind(this));
            },
        },
        created: function(){

            this.getBranches()

        }
    }
</script>