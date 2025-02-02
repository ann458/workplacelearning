import _ from "lodash";
import * as axios from "axios";

export default class ProducingActivityProcessExporter {

    constructor(type, activities) {
        this.type = type;
        this.activities = activities;

        this.outputData = '';

    }

    getFeedbackUrl(id) {
        return "https://" + window.location.hostname + '/producing/feedback/' + id;
    }

    csv() {

        // Build headers and filter unwanted
        let headers = Object.keys(this.activities[0]);

        let unwantedColumns = ["id", "url", "difficultyValue", "hours"];
        unwantedColumns.forEach(column => {
            headers.splice(headers.indexOf(column), 1)
        });

        let translatedHeaders = headers.map(header => {
            return exportTranslatedFieldMapping[header]
        });
        this.output(translatedHeaders.join(";") + "\n");

        this.activities.forEach((activity, index) => {
            let values = headers.map(header => {
                if (unwantedColumns.indexOf(header) !== -1) return;
                if (activity[header] === null || activity[header] === 'null') {
                    return '';
                }
                if(header === 'feedback') {
                    return this.getFeedbackUrl(activity[header]);
                }
                return activity[header];
            }).map(this.escapeCsv);
            let dataString = values.join(";");
            this.output(index < this.activities.length ? dataString + "\n" : dataString);

        });
    }

    txt() {
        // Build headers and filter unwanted
        let headers = Object.keys(this.activities[0]);

        let unwantedColumns = ["id", "url", "difficultyValue", "hours"];
        unwantedColumns.forEach(column => {
            headers.splice(headers.indexOf(column), 1)
        });

        this.activities.forEach((activity, index) => {
            let lines = headers.map(header => {
                if (unwantedColumns.indexOf(header) !== -1) return;
                if(header === 'description') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": \n\t" + activity[header] + " \n";
                }
                if (activity[header] === null || activity[header] === 'null') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": -";
                }
                if(header === 'feedback') {
                    return _.capitalize(exportTranslatedFieldMapping[header]) + ": " + this.getFeedbackUrl(activity[header]);
                }
                return _.capitalize(exportTranslatedFieldMapping[header]) + ": " + activity[header];
            });
            let dataString = lines.join("\n");
            this.output(index < this.activities.length ? dataString + "\n______________\n\n" : dataString);
        });
    }

    mail(email, comment, callback) {
        this.txt();

        axios.post('/activity-export-mail', {txt: this.outputData, email, comment})
            .then(callback)
            .catch(callback);
    }

    output(str) {
        this.outputData += str;
    }

    download() {
        let a = document.createElement('a');
        a.href = 'data:attachment/' + this.type + ',' + encodeURIComponent(this.outputData);
        a.target = '_blank';
        a.download = 'export.' + this.type;
        document.body.appendChild(a);
        a.click();
    }

    /**
     * @param string {string}
     */
    escapeCsv(string) {
        return '"' + string.replace(/"/g, '""') + '"';
    }

}