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


            this.survey.onUploadFiles
                .add(function (survey, options) {
                    options
                        .files
                        .forEach(function (file) {
                            var formData = new FormData();
                            formData.append("file", file);
                            $.ajax({
                                url: `/survey/uploadFile`,
                                type: "POST",
                                success: function (data) {
                                    var content = data.replace('dxsfile:', 'https://api.surveyjs.io/public/v1/Survey/file?filePath=');
                                    if (data.indexOf("dxsimage:") === 0) {
                                        content = data.replace('dxsimage:', 'https://api.surveyjs.io/public/v1/Survey/file?filePath=');
                                    }
                                    options.callback("success", [
                                        {
                                            file: file,
                                            content: content
                                        }
                                    ]);
                                },
                                error: function (error) {},
                                async: true,
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                timeout: 60000
                            });
                        });
                });



            this.survey.onComplete.add((result) => {
                // console.log(result)
                let url = `/survey/${this.surveyData.id}/result`
                axios.post(url, {json: result.data})
                    .then((response) => {
                        console.log(response)
                    })
            })
        }
    }
</script>
