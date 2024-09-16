<template>
    <input type="hidden" name="_token" :value="csrf" />
    <v-card color="red ">
        <v-card-title class="text-center justify-center py-6">
            <h1 class="font-weight-bold text-h2 text-basil">
                Broobe Challenge
            </h1>
        </v-card-title>

        <v-tabs v-model="tab" bg-color="transparent" color="basil" grow>
            <v-tab
                v-for="item in items"
                :key="item"
                :text="item.name"
                :value="item"
            ></v-tab>
        </v-tabs>

        <v-tabs-window v-model="tab">
            <v-tabs-window-item v-for="item in items" :key="item" :value="item">
                <v-card
                    v-if="
                        this.tab.isRunMetric != undefined &&
                        this.tab.isRunMetric
                    "
                    color="basil"
                    flat
                >
                    <v-card-text>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    type="text"
                                    v-model="url"
                                    placeholder="URL"
                                    outlined
                                ></v-text-field>
                            </v-col>
                            <v-col v-for="category in categories">
                                <v-checkbox
                                    v-model="selectedCategories"
                                    color="red-darken-3"
                                    :label="category.name"
                                    :value="category.name"
                                ></v-checkbox>
                            </v-col>
                            <v-col>
                                <v-select
                                    :items="strategies"
                                    v-model="strategy"
                                    item-title="name"
                                    label="Strategy"
                                >
                                </v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="2"
                                ><v-btn color="primary" @click="runMetrics()"
                                    >Run metrics</v-btn
                                ></v-col
                            >
                            <v-col cols="12" sm="2"
                                ><v-btn color="secondary" @click="saveMetrics()"
                                    >Save metrics</v-btn
                                ></v-col
                            >
                        </v-row>
                        <v-row>
                            <v-container>
                                <canvas id="myChart"></canvas>
                            </v-container>
                        </v-row>
                    </v-card-text>
                </v-card>
                <v-card
                    v-if="
                        this.tab.isMetricHistory != undefined &&
                        this.tab.isMetricHistory
                    "
                    color="basil"
                    flat
                >
                    <v-card-text>
                            <v-data-table
                                :headers="headers"
                                :items="metricsHistory"
                            >
                                <template v-slot:top>
                                    <v-toolbar flat>
                                        <v-toolbar-title
                                            >My CRUD</v-toolbar-title
                                        >
                                        <v-divider
                                            class="mx-4"
                                            inset
                                            vertical
                                        ></v-divider>
                                        <v-spacer></v-spacer>
                                    </v-toolbar>
                                </template>
                                <template v-slot:item.actions="{ item }">
                                    <v-btn color="red" @click="runNewTest(item)">Run New Test</v-btn>
                                </template>
                            </v-data-table>
                    </v-card-text>
                </v-card>
            </v-tabs-window-item>
        </v-tabs-window>
    </v-card>
</template>

<script>
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";
export default {
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            tab: { name: "Run Metric", isRunMetric: true },
            url: "",
            idMetric: 0,
            selectedCategories: [],
            isConsulted: false,
            strategies: [],
            categories: [],
            strategy: "",
            metricsResults: {
                accessibility: { name: "ACCESSIBILITY", score: 0.0 },
                best_practices: { name: "BEST PRACTICES", score: 0.0 },
                performance: { name: "PERFORMANCE", score: 0.0 },
                pwa: { name: "PWA", score: 0.0 },
                seo: { name: "SEO", score: 0.0 },
            },
            items: [
                { name: "Run Metric", isRunMetric: true },
                { name: "Metric History", isMetricHistory: false },
            ],
            headers: [
                {
                    title: "ID",
                    align: "start",
                    sortable: false,
                    key: "id",
                },
                { title: "URL", key: "url" },
                { title: "Accessibility Metric", key: "accessibility_metric" },
                { title: "PWA Metric", key: "pwa_metric" },
                { title: "Performance Metric", key: "performance_metric" },
                { title: "SEO Metric", key: "seo_metric" },
                { title: "Best Practices Metric", key: "best_practices_metric" },
                { title: "Strategy", key: "strategy" },
                { title: "Created At", key: "created_at" },
                { title: "Updated At", key: "updated_at" },
                { title: 'Actions', key: 'actions', sortable: false },
            ],
            metricsHistory: [],
            isUpdate:false,
        };
    },
    watch: {
        tab() {
            if (this.tab.isRunMetric != undefined) {
                this.resetTestData();
                this.isRunMetricHistory = true;
            } else if (this.tab.isMetricHistory != undefined) {
                this.tab.isMetricHistory = true;
                this.getMetricsHistory().then((response)=>{
                    this.metricsHistory = response;
                });
            }
        },
    },
    created() {
        this.getStrategies()
            .then((response) => {
                this.strategies = response;
            })
            .catch((err) => {
                console.log(err);
            });
        this.getCategories()
            .then((response) => {
                this.categories = response;
            })
            .catch((err) => {
                console.log(err);
            });

    },
    methods: {
        getStrategies() {
            return new Promise(async (resolve, reject) => {
                try {
                    let response;
                    await fetch("http://127.0.0.1:8000/getStrategies")
                        .then((res) => {
                            return res.json();
                        })
                        .then((e) => {
                            response = e;
                        });
                    resolve(response);
                } catch (e) {
                    reject(e);
                }
            });
        },
        getMetricsHistory()
        {
            return new Promise(async (resolve, reject) => {
                try {
                    let response;
                    await fetch("http://127.0.0.1:8000/getMetricsHistory")
                        .then((res) => {
                            return res.json();
                        })
                        .then((e) => {
                            response = e;
                        });
                    resolve(response);
                } catch (e) {
                    reject(e);
                }
            });
        },
        getCategories() {
            return new Promise(async (resolve, reject) => {
                try {
                    let response;
                    await fetch("http://127.0.0.1:8000/getCategories")
                        .then((res) => {
                            return res.json();
                        })
                        .then((e) => {
                            response = e;
                        });
                    resolve(response);
                } catch (e) {
                    reject(e);
                }
            });
        },
        resetTestData()
        {
            this.selectedCategories = [];
            this.strategy='';
            this.url='';
            this.isConsulted=false;
            this.isUpdate=false;
        },

        determinateCategoryTestUpdate(item)
        {
            let category = new Object();
            if(item.accesibility_metric != 0.0)
            {
                category = this.categories.find(cat=>cat.name == 'ACCESSIBILITY');
                this.selectedCategories.push(category.name)
            }
            if(item.pwa_metric != 0.0)
            {
                category = this.categories.find(cat=>cat.name == 'PWA');
                this.selectedCategories.push(category.name)
            }

            if(item.best_practices_metric != 0.0)
            {
                letcategory = this.categories.find(cat=>cat.name == 'BEST_PRACTICES');
                this.selectedCategories.push(category.name)
            }

            if(item.performance_metric != 0.0)
            {
                category = this.categories.find(cat=>cat.name == 'PERFORMANCE');
                this.selectedCategories.push(category.name)
            }

            if(item.seo_metric != 0.0)
            {
                category = this.categories.find(cat=>cat.name == 'SEO');
                this.selectedCategories.push(category.name)
            }

        },
        async runNewTest(item)
        {
            this.url = item.url;
            await this.determinateCategoryTestUpdate(item);            
            this.strategy = item.strategy;
            this.idMetric = item.id;
        
            this.isUpdate = true;
            if(await this.runMetrics())
            {
                this.isConsulted = true;
                return await this.updateMetrics(item.strategy_id)
            }
        },
        async requestUpdateMetrics(data)
        {
            const request = new Request("http://127.0.0.1:8000/updateMetrics", {
            method: "PUT",
            body: JSON.stringify(data),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": this.csrf,
            },
            });

            const response = await fetch(request);
            const result = await response.json();

            return result;
        },
        isValidUrl(url) {
            try {
                let reg =
                    /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
                return url == "" ||
                    typeof url != "string" ||
                    url.match(reg) == null
                    ? false
                    : true;
            } catch (err) {
                throw err;
            }
        },
        matchDataCategory(data, response, categories) {
            data.categories.forEach((category) => {
                let findedCategory = this.categories.find(
                    (cat) => cat.name == category
                );
                if (findedCategory != undefined) {
                    response.push(findedCategory);
                }
            });
            if (response.length != categories.length) {
                return false;
            }
            return response;
        },
        isEqualsCategoriesLength(data) {
            let response = [];
            if (data.categories.length == this.categories.length) {
                response = this.matchDataCategory(
                    data,
                    response,
                    this.categories
                );
                return response;
            } else {
                if (data.categories.length == 0) {
                    return false;
                }
                response = this.matchDataCategory(
                    data,
                    response,
                    data.categories
                );
                return response;
            }
        },
        validateUpdateMetricsData(data)
        {
            return this.isConsultedMetrics() && this.isValidUrl(data.url) && data.metric_id != '' && data.metric_id > 0
        },
        async updateMetrics(strategy_id)
        {
            try {
                this.isUpdate = true;
                let data = {
                    url: this.url,
                    metrics: this.metricsResults,
                    strategy_id: strategy_id,
                    metric_id : this.idMetric,
                };

                if (this.validateUpdateMetricsData(data)) {
                    Swal.fire({
                        title: "Consulting",
                        html: "Wait, we are consulting",
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    let result = await this.requestUpdateMetrics(data);
                    return this.isValidResponseUpdateMetrics(result);
                } else {
                    return false;
                }
            } catch (err) {
                throw err;
            }
        },
        isValidResponseUpdateMetrics(result) {
            if (result.status == 200) {
                if (result.code == 1) {
                    Swal.fire(
                        "The metrics have been updated correctly",
                        "",
                        "success"
                    );
                    this.metricsHistory = result.response;
                    this.isConsulted = false;
                    return true;
                }
            } else {
                if (result.code == 0) {
                    Swal.fire(
                        "An error occurred processing the request! Please try later",
                        "",
                        "error"
                    );
                } else {
                    Swal.fire(result.response);
                }
                return false;
            }
            return false;
        },
        validateDataCategories(data) {
            try {
                return typeof data.categories != "object"
                    ? false
                    : this.isEqualsCategoriesLength(data);
            } catch (err) {
                throw err;
            }
        },
        validateStrategy(strategy) {
            try {
                if (
                    strategy.toUpperCase() != "MOBILE" &&
                    strategy.toUpperCase() != "DESKTOP"
                ) {
                    return false;
                }
                return true;
            } catch (err) {
                throw err;
            }
        },
        validateRunMetricsData(data) {
            if (!this.isValidUrl(data.url)) {
                Swal.fire("Invalid URL", "", "warning");
                return false;
            }
            if (!this.validateDataCategories(data)) {
                Swal.fire("Invalid Category", "", "warning");
                return false;
            }
            if (!this.validateStrategy(data.strategy)) {
                Swal.fire("Invalid Strategy", "", "warning");
                return false;
            }
            return true;
        },
        async requestRunMetrics(data) {
            const headers = new Headers();
            headers.append("Content-Type", "application/json");
            headers.append("Access-Control-Allow-Origin", "*");
            headers.append("X-CSRF-Token", this.csrf);

            const request = new Request("http://127.0.0.1:8000/runMetrics", {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": this.csrf,
                },
            });

            const response = await fetch(request);
            const result = await response.json();

            return result;
        },
        isValidResponseRunMetrics(result) {
            if (result.status == 200) {
                if (result.code == 1) {
                    Swal.fire(
                        "The metrics have been consulted correctly",
                        "",
                        "success"
                    );
                    this.metricsResults.accessibility.score =
                        result.response.lighthouseResult.categories
                            .accessibility != undefined
                            ? result.response.lighthouseResult.categories
                                  .accessibility.score
                            : 0.0;
                    this.metricsResults.best_practices.score =
                        result.response.lighthouseResult.categories
                            .best_practices != undefined
                            ? result.response.lighthouseResult.categories
                                  .best_practices.score
                            : 0.0;
                    this.metricsResults.performance.score =
                        result.response.lighthouseResult.categories
                            .performance != undefined
                            ? result.response.lighthouseResult.categories
                                  .performance.score
                            : 0.0;
                    this.metricsResults.pwa.score =
                        result.response.lighthouseResult.categories.pwa !=
                        undefined
                            ? result.response.lighthouseResult.categories.pwa
                                  .score
                            : 0.0;
                    this.metricsResults.seo.score =
                        result.response.lighthouseResult.categories.seo !=
                        undefined
                            ? result.response.lighthouseResult.categories.seo
                                  .score
                            : 0.0;
                    if(!this.isUpdate)
                    {
                        this.createChart();
                    }
                    this.isConsulted = true;

                    return true;
                }
            } else {
                if (result.code == 0) {
                    Swal.fire(
                        "An error occurred processing the request! Please try later",
                        "",
                        "error"
                    );
                } else {
                    Swal.fire(result.response, "", "error");
                }
                return false;
            }
            return false;
        },
        async runMetrics() {
            try {
                let data = {
                    url: this.url,
                    categories: this.selectedCategories,
                    strategy: this.strategy,
                };

                if (this.validateRunMetricsData(data)) {
                    Swal.fire({
                        title: "Consulting",
                        html: "Wait, we are consulting",
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    let result = await this.requestRunMetrics(data);
                    return await this.isValidResponseRunMetrics(result);
                } else {
                    return false;
                }
            } catch (err) {
                throw err;
            }
        },
        async requestSaveMetrics(data) {
            const request = new Request("http://127.0.0.1:8000/saveMetrics", {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": this.csrf,
                },
            });

            const response = await fetch(request);
            const result = await response.json();

            return result;
        },
        isValidResponseSaveMetrics(result) {
            if (result.status == 200) {
                if (result.code == 1) {
                    Swal.fire(
                        "The metrics have been saved correctly",
                        "",
                        "success"
                    );
                    this.metricsHistory = result.response;
                    this.isConsulted = false;
                    return true;
                }
            } else {
                if (result.code == 0) {
                    Swal.fire(
                        "An error occurred processing the request! Please try later",
                        "",
                        "error"
                    );
                } else {
                    Swal.fire(result.response);
                }
                return false;
            }
            return false;
        },
        isConsultedMetrics()
        {
            if (!this.isConsulted) {
                Swal.fire("You cannot save without consulting the metrics",'','warning');
                return false;
            }
            return true;
        },
        validateSaveMetricsData(data) {
            return this.isConsultedMetrics() && this.isValidUrl(data.url)
        },
        async saveMetrics() {
            if(!this.isConsultedMetrics()){return false;}
            if(!this.validateStrategy(this.strategy)){return false;}
            let strategy_id = this.strategies.find(
                (strat) => strat.name == this.strategy
            );
            let data = {
                url: this.url,
                metrics: this.metricsResults,
                strategy_id: strategy_id.id,
            };
            if (this.validateSaveMetricsData(data)) {
                let result = await this.requestSaveMetrics(data);
                return this.isValidResponseSaveMetrics(result);
            }

            return false;
        },
        createChart() {
            const ctx = document.getElementById("myChart");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: [
                        this.metricsResults.accessibility.name,
                        this.metricsResults.best_practices.name,
                        this.metricsResults.performance.name,
                        this.metricsResults.pwa.name,
                        this.metricsResults.seo.name,
                    ],
                    datasets: [
                        {
                            label: "# of Votes",
                            data: [
                                this.metricsResults.accessibility.score,
                                this.metricsResults.best_practices.score,
                                this.metricsResults.performance.score,
                                this.metricsResults.pwa.score,
                                this.metricsResults.seo.score,
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        },
    },
};
</script>
