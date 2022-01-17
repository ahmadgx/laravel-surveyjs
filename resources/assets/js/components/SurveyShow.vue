<template>
    <div>
        <survey :survey="survey"></survey>
    </div>
</template>

<script>
    import * as SurveyVue from 'survey-vue'

    const Survey = SurveyVue.Survey
    SurveyVue.StylesManager.applyTheme(SurveyConfig.theme)

    import * as widgets from "surveyjs-widgets";

    Object.filter = (obj, predicate) =>
        Object.keys(obj)
            .filter( key => predicate(obj[key]) )
            .reduce( (res, key) => Object.assign(res, { [key]: obj[key] }), {} );

    const widgetsList = Object.filter(SurveyConfig.widgets, widget => widget === true);

    Object.keys(widgetsList).forEach(function (widget) {
        widgets[widget](SurveyVue);
    });

    export default {
        components: {
            Survey
        },
        props: ['surveyData'],
        data () {
            return {
                survey: {},
                attach: []
            }
        },
        created () {
            this.survey = new SurveyVue.Model(this.surveyData.json)
        },
        mounted () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            this.survey.onUploadFiles.add(function (survey, options) {
                    options
                        .files
                        .forEach(function (file) {
                            var formData = new FormData();
                            formData.append("file", file);
                            $.ajax({
                                url: `/api/form/upload-file`,
                                type: "POST",
                                success: function (data) {
                                    var content = data.data;
                                    options.callback("success", [
                                        {
                                            file: file,
                                            content: content
                                        }
                                    ]);
                                },
                                error: function (error) {
                                    options.callback("error", [
                                        {
                                            file: file,
                                            response: error.responseJSON.message
                                        }
                                    ]);
                                     // options.error[file] = ;
                                },
                                async: true,
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                timeout: 60000
                            });
                        });
                });


            // this.survey.onServerValidateQuestions.add(function (survey, options) {
            //     //options.data contains the data for the current page.
            //     var email = options.data["email"];
            //     //If the question is empty then do nothing
            //     if (!email) {
            //         options.complete();
            //         return;
            //     }
            //     //call the ajax method
            //     $
            //         .ajax({
            //             url: `/api/survey/back-end-validation`,
            //             type: "POST",
            //         })
            //         .then(function (data) {
            //             var found = data.length > 0;
            //             //if the country is unknown, add the error
            //             if (!found)
            //                 options.errors["email"] = "Duplicate email";
            //
            //             //tell survey that we are done with the server validation
            //             options.complete();
            //         });
            // });

            this.survey.onComplete.add((result) => {
                // console.log(result)
                let url = `/api/form/${this.surveyData.id}/result`
                axios.post(url, {json: result.data})
                    .then((response) => {
                        console.log(response)
                    })
            })
        }
    }
</script>
